<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product\Product;

class Barcode extends Model
{
    use HasFactory;

    protected $table = 'barcodes';

    protected $fillable = [
        'barcode_image',
        'barcode_number',
        'product_id',
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
