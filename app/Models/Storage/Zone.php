<?php

namespace App\Models\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Storage\Rack;
use App\Models\Storage\Location;
use App\Models\Product\Product;

class Zone extends Model
{
    use HasFactory;

    protected $table = 'zones';

    protected $fillable = [
        'zone_image',
        'zone_name',
        'location',
    ];

    public function racks(): HasManyThrough {
        return $this->hasManyThrough(Rack::class, Location::class, 'zone_id', 'rack_id');
    }

    public function locations(): HasMany {
        return $this->hasMany(Location::class, 'zone_id');
    }

    public function products(): HasMany {
        return $this->hasMany(Product::class, 'zone_id');
    }
}
