<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_id', 'name', 'image_path'];

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class, 'catalog_product')
            ->withPivot('price')
            ->withTimestamps();
    }
}
