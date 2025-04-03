<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Product\Barcode;
use App\Models\Product\Image;
use App\Models\Master\Category\Category;
use App\Models\Master\Category\SubCategory;
use App\Models\Master\Storage\Zone;
use App\Models\Master\Storage\Rack;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'cover_image',
        'name',
        'description',
        'price',
        'quantity',
        'sku_code',
        'category_id',
        'zone_id',
        'rack_id',
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images(): HasMany {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function barcode(): HasOne {
        return $this->hasOne(Barcode::class, 'product_id');
    }

    public function zone(): BelongsTo {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function rack(): BelongsTo {
        return $this->belongsTo(Rack::class, 'rack_id');
    }
}
