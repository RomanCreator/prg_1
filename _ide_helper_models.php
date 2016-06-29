<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\RolePermission
 *
 * @property integer $rp_id
 * @property string $rp_role_name
 * @property string $rp_entity_name
 * @property string $rp_action
 * @method static \Illuminate\Database\Query\Builder|\App\RolePermission whereRpId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RolePermission whereRpRoleName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RolePermission whereRpEntityName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RolePermission whereRpAction($value)
 */
	class RolePermission extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * Class Hospital
 * Медицинское учреждение
 *
 * @package App
 * @property integer $hos_id
 * @property string $hos_name
 * @property string $hos_description
 * @property string $hos_logo
 * @property string $hos_address
 * @property string $hos_technical_address
 * @property string $hos_description_about
 * @property boolean $hos_status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereHosId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereHosName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereHosDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereHosLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereHosAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereHosTechnicalAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereHosDescriptionAbout($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereHosStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereUpdatedAt($value)
 */
	class Hospital extends \Eloquent {}
}

namespace App{
/**
 * App\Role
 *
 * @property string $name_role
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereNameRole($value)
 */
	class Role extends \Eloquent {}
}

