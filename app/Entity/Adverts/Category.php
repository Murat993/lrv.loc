<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;


/**
 * App\Entity\Category
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property Attribute[] $attributes
 * @property-read Category[] $children
 */
class Category extends Model
{
    use NodeTrait;

    public $timestamps = false;

    protected $table = 'advert_categories';

    protected $fillable = ['name', 'slug', 'parent_id'];

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }
}
