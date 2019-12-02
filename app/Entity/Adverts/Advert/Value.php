<?php

namespace App\Entity\Adverts\Advert;


use App\Entity\Adverts\Category;
use App\Entity\Regions;
use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Adverts\Advert\Value
 * @property int advert_id
 * @property int attribute_id
 * @property string value
 * @mixin \Eloquent
 */
class Value extends Model
{
    protected $table = 'advert_advert_values';

    public $timestamps = false;

    protected $fillable = ['value', 'attribute_id', 'advert_id'];



}
