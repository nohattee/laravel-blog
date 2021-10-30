<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    /**
     * The products that use attribute.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->using(ProductAttributeValue::class);
    }
}
