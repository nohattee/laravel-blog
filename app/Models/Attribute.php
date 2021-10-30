<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    /**
     * Get the values for the attribute.
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
