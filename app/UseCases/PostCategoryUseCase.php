<?php

namespace App\UseCases;

use App\Models\PostCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PostCategoryUseCase {

     /**
     * @var $postCategory
     */
    protected $postCategory;

    public static $rules = [
        'name' => 'required',
        'description' => '',
        'parent_id' => 'nullable|exists:post_categories,id'
    ];

    /**
     * Create the PostCategoryUseCase instance.
     *
     * @return void
     */
    public function __construct(PostCategory $postCategory) 
    {
        $this->postCategory = $postCategory;
    }

    public function validate(array $attributes = [])
    {
        $validator = Validator::make($attributes, static::$rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        
        return $validator->validated();
    }

    public function create(array $attributes = []) 
    {
        $validated = $this->validate($attributes);
        $postCategory = $this->postCategory->create($validated);
        return $postCategory;
    }

    /**
     * Update the model in the database.
     *
     * @param  array         $attributes
     * @param  PostCategory  $postCategory
     * @return bool
     */
    public function update(array $attributes = [], PostCategory $postCategory) 
    {
        $validated = $this->validate($attributes);
        $result = $postCategory->update($validated);
        return $result;
    }
}