<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $nick_name 
 * @property string $img_url 
 * @property string $openid 
 * @property int $is_subscribe 
 * @property string $subscribe_time 
 * @property string $mobile 
 * @property int $src 
 * @property string $user_name 
 * @property string $password 
 * @property string $create_time 
 * @property string $update_time 
 * @property string $last_login 
 */
class Member extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'member';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'is_subscribe' => 'integer', 'src' => 'integer'];
}