<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category\Category;
use App\Models\Category\SubCategory;

class Mapping extends Model
{
    use HasFactory;

    protected $table = 'category_mappings';

    protected $fillable = [
        'category_id',
        'subcategory_id',
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory(): BelongsTo {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
}
