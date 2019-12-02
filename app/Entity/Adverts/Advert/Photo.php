<?php


namespace App\Entity\Adverts\Advert;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Adverts\Advert\Value
 * @property int id
 * @property int advert_id
 * @property string file
 * @mixin \Eloquent
 */
class Photo extends Model
{
    protected $table = 'advert_advert_photos';

    public $timestamps = false;

    protected $fillable = ['file'];



}
