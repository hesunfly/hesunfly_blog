<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Scout\Searchable;

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

//    use Searchable;

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
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'status' => 'integer',
        'view_count' => 'integer',
        'publish_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select('id', 'title');
    }

    public function getPublishAtAttribute($value)
    {
        return $value ? mb_substr($value, 0, 16) : '';
    }

    public function searchableAs(): string
    {
        return 'article';
    }

    public function toSearchableArray(): array
    {
        return [
            'publish_at' => $this->publish_at,
            'title' => $this->title,
            'content' => $this->content,
            'category' => Category::query()->where('id', $this->category_id)->value('title'),
        ];
    }

    //只有发布的文章可以进行检索
    public function shouldBeSearchable()
    {
        return $this->status == 1;
    }
}