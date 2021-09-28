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
        'slug' => '',
        'thumbnail' => 'URL',
        'author_id' => 'integer',
        'post_status' => '',
        'post_categories' => 'array',
    ];
}
