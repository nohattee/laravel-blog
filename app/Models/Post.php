<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public static $rules = [
        'title' => 'required',
        'content' => 'required',
        'slug' => 'URL',
        'thumbnail' => 'URL',
        'author_id' => 'integer',
        'post_categories' => 'array',
    ];
}
