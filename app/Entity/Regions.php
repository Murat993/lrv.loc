<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;



/**
 * App\Entity\Regions
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property Regions $parent
 * @property Regions[] $children
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Regions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Regions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Regions query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Regions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Regions whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Regions whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Regions whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Regions roots()
 * @mixin \Eloquent
 */
class Regions extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'parent_id'];

    public function getAddress(): string
    {
        return ($this->parent ? $this->parent->getAddress() . ', ' : '') . $this->name;
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function scopeRoots(Builder $query)
    {
        return $query->where(['parent_id' => null]);
    }
}
