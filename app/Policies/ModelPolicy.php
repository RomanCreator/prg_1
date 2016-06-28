<?php

namespace App\Policies;

use App\RolePermission;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Model;

class ModelPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Проверка на возможность добалвения сущности
     *
     *
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function addModel (User $user, Model $model) {
        return $this->checkPermission($user, $model, 'add');
    }


    /**
     * Проверка на возможность просмотра
     *
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function editModel (User $user, Model $model) {
        return $this->checkPermission($user, $model, 'edit');
    }

    /**
     * Проверка на возможность просмотра полей сущности
     *
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function viewModel (User $user, Model $model) {
        return $this->checkPermission($user, $model, 'view');
    }

    /**
     * Проверка возможности удаления сущности
     *
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function deleteModel (User $user, Model $model) {
        return $this->checkPermission($user, $model, 'delete');
    }

    /**
     * Проверка на возможность просмотра списка сущностей
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function listModel (User $user, Model $model) {
        return $this->checkPermission($user, $model, 'list');
    }


    /**
     * Функция проверки доступности некоторого действия
     *
     * @param User $user
     * @param Model $model
     * @param $action
     * @return bool
     */
    protected function checkPermission (User $user, Model $model, $action) {
        $userRoles = $user->roles();
        $modelClassName = $model::class;

        foreach ($userRoles as $userRole) {
            $rolePermission = RolePermission::where([
                ['rp_role_name', $userRole->name_role],
                ['rp_entity_name', $modelClassName],
                ['rp_action', $action]
            ])->first();
            if ($rolePermission) {
                return true;
            }
        }

        return false;
    }
}
