<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 */
class StaticPage extends Model
{
    protected $fillable = ['title', 'keywords', 'description', 'content'];
}
