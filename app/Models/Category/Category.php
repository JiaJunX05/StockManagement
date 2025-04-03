<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Category\SubCategory;
use App\Models\Category\Mapping;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'category_image',
        'category_name',
    ];

    public function subCategories(): HasManyThrough {
        return $this->hasManyThrough(SubCategory::class, Mapping::class, 'category_id', 'subcategory_id');
    }

    public function mappings(): HasMany {
        return $this->hasMany(Mapping::class, 'category_id');
    }

    public function products(): HasMany {
        return $this->hasMany(Product::class, 'category_id');
    }
}
