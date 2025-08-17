<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['title'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'catalog_product')
            ->withPivot('price')
            ->withTimestamps();
    }
}
