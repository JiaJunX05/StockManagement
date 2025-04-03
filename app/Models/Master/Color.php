<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product\Product;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colors';

    protected $fillable = [
        'color_name',
        'hex_code',
        'rgb_code',
    ];

    public function products(): HasMany {
        return $this->hasMany(Product::class, 'color_id');
    }
}
