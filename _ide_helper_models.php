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
 * App\StaticPage
 *
 * @property integer $id
 * @property string $path
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property string $entity
 * @property integer $id_entity
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage wherePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereEntity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereIdEntity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StaticPage whereUpdatedAt($value)
 */
	class StaticPage extends \Eloquent {}
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
 * App\Research
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property boolean $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Research whereUpdatedAt($value)
 */
	class Research extends \Eloquent {}
}

namespace App{
/**
 * App\Price
 *
 * @property integer $id
 * @property integer $hospital_id
 * @property integer $research_id
 * @property float $price_from
 * @property float $price_to
 * @property string $description
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Hospital $hospital
 * @property-read \App\Research $research
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereHospitalId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereResearchId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price wherePriceFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price wherePriceTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Price whereUpdatedAt($value)
 */
	class Price extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $middlename
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereSurname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereMiddlename($value)
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
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property string $technical_address
 * @property string $description_about
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereTechnicalAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereDescriptionAbout($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Hospital whereUpdatedAt($value)
 */
	class Hospital extends \Eloquent {}
}

