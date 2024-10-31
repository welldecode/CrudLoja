<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categories extends Model
{
    use HasFactory;
    //

    protected $fillable = [
        'name',
        'description',
        'is_available',
    ];
    

    public function products():  HasMany {
        return $this->hasMany(Products::class, 'category_id', 'id');
    }
}
