<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attribute values that belong to the product.
     */
    public function attributeValues()
    {
        return $this->belongsToMany(Product::class)->using(ProductAttributeValue::class);
    }

    /**
     * The attribute values that belong to the product.
     */
    public function attributes()
    {
        return $this->hasManyThrough(Attribute::class, ProductAttributeValue::class);
    }
}
