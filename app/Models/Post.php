<?php

namespace App\Models;

use Exception;
use App\Traits\Sluggable;
use App\Traits\Filterable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Sluggable, Filterable, SoftDeletes;

    private $post_categories = [];

    protected $generateSlugFrom = 'title';

    public static $rules = [
        'title' => 'required',
        'content' => 'required',
        'slug' => '',
        'thumbnail' => 'URL',
        'author_id' => 'integer',
        'post_status' => '',
        'post_categories' => 'array',
    ];

    /**
     * The attributes that are filterable.
     *
     * @var array
     */
    protected $filterable = [
        'title',
        'slug',
        'post_status',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'slug',
        'thumbnail',
        'post_status',
        'author_id',
        'post_categories',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($post) {
            if (empty($post->post_categories)) {
                return;
            }

            DB::beginTransaction();
            try {
                $post->categories()->sync($post->post_categories);
            } catch (Exception $e) {
                DB::rollback();
                dd($e);
            }
            DB::commit();
        });
    }

    public function setPostCategoriesAttribute(array $value) 
    {
        $this->post_categories = $value;
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function categories() 
    {
        return $this->belongsToMany(PostCategory::class, 'post_post_categories');
    }
}
