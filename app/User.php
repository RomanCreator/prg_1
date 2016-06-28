<?php

namespace App;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Authorizable;
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
     */
    public function roles () {
        return $this->belongsToMany(Role::class, 'users_role');
    }

    /**
     * Проверяет принадлежность пользователя к той или иной роли
     *
     * @param $roleName Название роли пользователя
     * @return bool
     */
    public function hasRole ($roleName) {
        $UserRole = $this->roles()->where(['name_role'=> $roleName])->first();
        if ($UserRole) {
            return true;
        } else {
            return false;
        }
    }
}
