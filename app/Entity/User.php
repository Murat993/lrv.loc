<?php

namespace App\Entity;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * App\Entity\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property int $status
 * @property string $role
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

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    const USER_STATUSES = [
        self::STATUS_WAIT => 'Wait',
        self::STATUS_ACTIVE => 'Active',
    ];

    const ROLE_STATUSES = [
        User::ROLE_USER => 'User',
        User::ROLE_ADMIN => 'Admin',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'verify_token', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_token' => Str::random(),
            'status' => User::STATUS_WAIT,
            'role' => User::ROLE_USER,
        ]);
    }

    public static function new(string $name, string $email): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random()),
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLE_USER,
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function verify(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already verified.');
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }

    public function changeRole($role):void
    {
        if (!in_array($role, [self::ROLE_ADMIN, self::ROLE_USER], true)) {
            throw new \InvalidArgumentException('Undefined role "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }

        $this->update(['role' => $role]);
    }
}
