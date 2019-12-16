<?php

namespace App\Console\Commands\Search;


use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Console\Command;
use Elasticsearch\Client;

class InitCommand extends Command
{
    protected $signature = 'search:init';
    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle(): bool
    {
        $this->initAdverts();
        $this->initBanners();
        return true;
    }

    public function initAdverts(): bool
    {
        try {
            $this->client->indices()->delete([
                'index' => 'adverts'
            ]);
        } catch (Missing404Exception $e) {}

        $this->client->indices()->create([
            'index' => 'adverts', // назване базы
            'body' => [
                'mappings' => [ // настройка описания каждого поля
                    'advert' => [ // название типа в данном случае advert
                        '_source' => [
                            'enabled' => true, // возвращать данные с элакстика или не возвращать
                        ],
                        'properties' => [ // настройка свойств
                            'id' => [
                                'type' => 'integer',
                            ],
                            'published_at' => [
                                'type' => 'date',
                            ],
                            'title' => [
                                'type' => 'text', // по LIKE
                            ],
                            'content' => [
                                'type' => 'text',
//                                'analyzer' => 'myown'  // подключить для этого поля свой анализатор
                            ],
                            'price' => [
                                'type' => 'integer',
                            ],
                            'status' => [
                                'type' => 'keyword', // тип для текста без индексации по равенству
                            ],
                            'categories' => [ // связанные таблицы будут в масиве (айдишники)
                                'type' => 'integer',
                            ],
                            'regions' => [
                                'type' => 'integer',
                            ],
                            'values' => [ // атрибуты
                                'type' => 'nested', // вложенные элементы (под массив)
                                'properties' => [
                                    'attribute' => [
                                        'type' => 'integer'
                                    ],
                                    'value_string' => [
                                        'type' => 'keyword',
                                    ],
                                    'value_int' => [
                                        'type' => 'integer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'settings' => [ // настройки анализатора
                    'analysis' => [
                        'char_filter' => [ //фильтр меняет & на and
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => [
                                    '&=> and '
                                ],
                            ],
                        ],
                        'filter' => [ // фильтр настроек для слов
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ],
                            'trigrams' => [ // разбивает текст (первые 4 вхождения)
                                'type' => 'ngram',
                                'min_gram' => 4,
                                'max_gram' => 6,
                            ],
                        ],
                        'analyzer' => [ // для настройки текстовых полей
                            'default' => [ // переопеределения анализатора по умолчанию (можно добавить свой)
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',  // удаляет html теги
                                    'replace',
                                ],
                                'tokenizer' => 'whitespace',
                                'filter' => [
                                    'lowercase',
                                    'word_delimiter', // подключаем настройку word_delimiter
                                    'trigrams',// подключаем настройку trigrams
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        return true;
    }

    private function initBanners(): void
    {
        try {
            $this->client->indices()->delete([
                'index' => 'banners'
            ]);
        } catch (Missing404Exception $e) {
        }
        $this->client->indices()->create([
            'index' => 'banners',
            'body' => [
                'mappings' => [
                    'banner' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'status' => [
                                'type' => 'keyword',
                            ],
                            'format' => [
                                'type' => 'keyword',
                            ],
                            'categories' => [
                                'type' => 'integer',
                            ],
                            'regions' => [
                                'type' => 'integer',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
