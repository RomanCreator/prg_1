<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Возвращает коллекцию ролей пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles () {
        return $this->hasMany('App\UsersRole');
    }

    /**
     * Проверяет принадлежность пользователя к той или иной роли
     *
     * @param $roleName Название роли пользователя
     * @return bool
     */
    public function hasRole ($roleName) {
        $UserRole = $this->roles()->where('role_id', $roleName);
        if ($UserRole) {
            return true;
        } else {
            return false;
        }
    }
}
