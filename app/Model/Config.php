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
    protected $guarded = [];

    protected $hidden = ['id', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'page_size' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static $setting_title = [
        'page_size' => [
            'title' => '首页分页数',
            'en_title' => 'Front Page Size',
            'default' => '15',
        ],
        'icp_record' => [
            'title' => '备案号',
            'en_title' => 'ICP Info',
            'default' => '',
        ],
        'reward_code_img' => [
            'title' => '赞赏收款码',
            'en_title' => 'Reward Code Img',
            'default' => '',
        ],
        'reward_desc' => [
            'title' => '赞赏码描述语',
            'en_title' => 'Reward Desc',
            'default' => '赞赏一下👍',
        ],
        'email' => [
            'title' => '邮箱',
            'en_title' => 'Email',
            'default' => '',
        ],
        'github' => [
            'title' => 'github地址',
            'en_title' => 'Github',
            'default' => 'https://github.com/hesunfly',
        ],
        'blog_name' => [
            'title' => '网站名称',
            'en_title' => 'App Name',
            'default' => 'Hesunfly Blog',
        ],
        'logo_img' => [
            'title' => '网站logo',
            'en_title' => 'Logo Img',
            'default' => '/assets/images/Hesunfly-Blog-Logo.png',
        ],
        'qr_img' => [
            'title' => '文章二维码水印图',
            'en_title' => 'Article Qr Img',
            'default' => '/assets/images/hesunfly-qr.png',
        ]
    ];
}