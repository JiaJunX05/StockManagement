<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Category\Category;
use App\Models\Category\Mapping;
use App\Models\Product;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'subcategories';

    protected $fillable = [
        'subcategory_image',
        'subcategory_name',
    ];

    public function categories(): HasManyThrough {
        return $this->hasManyThrough(Category::class, Mapping::class, 'subcategory_id', 'category_id');
    }

    public function mappings(): HasMany {
        return $this->hasMany(Mapping::class, 'subcategory_id');
    }

    public function products(): HasMany {
        return $this->hasMany(Product::class, 'subcategory_id');
    }
}
