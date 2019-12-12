<?php

namespace App\UseCases\Adverts;


use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Regions;
use App\Http\Requests\Adverts\SearchRequest;
use Elasticsearch\Client;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Query\Expression;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(?Category $category, ?Regions $region, SearchRequest $request, int $perPage, int $page): SearchResult
    {
        $values = array_filter((array)$request->input('attrs'), function ($value) {
            return !empty($value['equals']) || !empty($value['from']) || !empty($value['to']);
        });

        $response = $this->client->search([
            'index' => 'app', // название базы
            'type' => 'advert', // название типа
            'body' => [
                '_source' => ['id'], // возвращает только айдишники (типо select)
                'from' => ($page - 1) * $perPage, // от какой страницы
                'size' => $perPage, // сколько страниц
                'sort' => empty($request['text']) ? [ // сортировка
                    ['published_at' => ['order' => 'desc']],
                ] : [],
                'aggs' => [
                    'group_by_region' => [
                        'terms' => [
                            'field' => 'regions',
                        ],
                    ],
                    'group_by_category' => [
                        'terms' => [
                            'field' => 'categories',
                        ],
                    ],
                ],
                'query' => [ // создания запроса
                    'bool' => [ // склеивается запрос через and
                        'must' => array_merge( // совпадение всех внутри масива
                            [
                                ['term' => ['status' => Advert::STATUS_ACTIVE]], // точное совпадение
                            ],
                            array_filter([ // отфильтровывает пустые значения
                                $category ? ['term' => ['categories' => $category->id]] : false, // по точному совпадению
                                $region ? ['term' => ['regions' => $region->id]] : false,
                                !empty($request['text']) ? ['multi_match' => [ // для поиска по нескольхим полям
                                    'query' => $request['text'],
                                    'fields' => [ 'title^3', 'content' ]
                                ]] : false,
                            ]),
                            array_map(function ($value, $id) {
                                return [
                                    'nested' => [ // тип для вложенного массива
                                        'path' => 'values',
                                        'query' => [
                                            'bool' => [
                                                'must' => array_values(array_filter([ // убрать не отсортированные ключи
                                                    ['match' => ['values.attribute' => $id]],
                                                    !empty($value['equals']) ? ['match' => ['values.value_string' => $value['equals']]] : false,
                                                    !empty($value['from']) ? ['range' => ['values.value_int' => ['gte' => $value['from']]]] : false,
                                                    !empty($value['to']) ? ['range' => ['values.value_int' => ['lte' => $value['to']]]] : false,
                                                ])),
                                            ],
                                        ],
                                    ],
                                ];
                            }, $values, array_keys($values))
                        )
                    ],
                ],
            ],
        ]);

        $ids = array_column($response['hits']['hits'], '_id');
        if (!$ids) {
            $items = Advert::active()
                ->with(['category', 'region'])
                ->whereIn('id', $ids)
                ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'))
                ->get();
            $pagination = new LengthAwarePaginator($items, $response['hits']['total'], $perPage, $page);
        } else {
            dd('asdas');
            $pagination = new LengthAwarePaginator([], 0, $perPage, $page);
        }

        return new SearchResult(
            $pagination,
            array_column($response['aggregations']['group_by_region']['buckets'], 'doc_count', 'key'),
            array_column($response['aggregations']['group_by_category']['buckets'], 'doc_count', 'key')
        );
    }
}
