<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $blog_name 
 * @property string $logo_img 
 * @property int $page_size 
 * @property string $icp_record 
 * @property string $reward_code_img 
 * @property string $reward_desc 
 * @property string $email 
 * @property string $github 
 * @property string $gitee 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Config extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'config';
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
    protected $casts = ['id' => 'integer', 'page_size' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}