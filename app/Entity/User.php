<?php

namespace App\Entity;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Entity\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\UsernewQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Userquery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\UserwhereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\UserwhereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\UserwhereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\UserwhereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\UserwherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\UserwhereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\UserwhereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    const USER_STATUSES = [
        self::STATUS_WAIT => 'Wait',
        self::STATUS_ACTIVE => 'Active',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'verify_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
