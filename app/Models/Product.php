<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Barcode;
use App\Models\Category;
use App\Models\Image;
use App\Models\Zone;
use App\Models\Rack;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'feature',
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
