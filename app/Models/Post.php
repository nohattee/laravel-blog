<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Sluggable;

    protected $generateSlugFrom = 'title';

    public static $rules = [
        'title' => 'required',
        'content' => 'required',
        'slug' => 'required',
        'thumbnail' => 'URL',
        'author_id' => 'integer',
        'post_status' => '',
        'post_categories' => 'array',
    ];

    public function scopeFilter($query, $params)
    {
        $results = [];

        $placeholder = new stdClass;

        foreach (static::$filters as $filter) {
            $value = data_get($params, $filter, $placeholder);

            if ($value !== $placeholder) {
                Arr::set($results, $filter, $value);
            }
        }

        return $query->where($results);
    }

    public function author() 
    {
        return $this->belongsTo(User::class);
    }
}
