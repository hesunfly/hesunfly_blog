<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property int $category_id 
 * @property string $title 
 * @property string $description 
 * @property string $slug 
 * @property string $html_content 
 * @property string $content 
 * @property int $status 
 * @property int $view_count 
 * @property string $qr_path 
 * @property string $publish_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Article extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'category_id' => 'integer', 'status' => 'integer', 'view_count' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select('id', 'title');
    }
}