<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

trait Sluggable
{
    public function getGenerateSlugFrom () {
        return isset($this->generateSlugFrom) ? $this->generateSlugFrom : 'name';
    }

    public function getSlugProperty () {
        return isset($this->slugProperty) ? $this->slugProperty : 'slug';
    }

    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootSluggable()
    {
        static::saving(function ($model) {
            $slugAttribute = $model->getSlugProperty();
            $generateSlugFrom = $model->getGenerateSlugFrom();
            if (is_null($model->$slugAttribute)) {
                $model->$slugAttribute = $model->generateSlug($model->$generateSlugFrom);
            }
        });
    }

    // /**
    //  * Set slug attribute.
    //  *
    //  * @param $value
    //  */
    // public function setSlugAttribute($value)
    // {
    //     $this->attributes[$this->slugAttribute] = $this->generateSlug($value);
    // }

    /**
     * Generate slug by the given value.
     *
     * @param string $value
     * @return string
     */
    private function generateSlug($value)
    {
        $slug = Str::slug($value, '-');

        $slugClone = $slug;
        while(!$this->isUniqueSlug($slug)) {
            $slug = $slugClone . '-' . rand(10000, 99999);
        }

        return $slug;
    }

    /**
     * Check if the slug is unique.
     *
     * @param string $slug
     * @return bool
     */
    private function isUniqueSlug($slug)
    {
        $query = $this->where('slug', $slug);
        if (Arr::has(class_uses($this), SoftDeletes::class)) {
            $query->withTrashed();
        }

        if ($query->exists()) {
            return false;
        }

        return true;
    }
}
