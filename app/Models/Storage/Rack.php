<?php

namespace App\Models\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Storage\Zone;
use App\Models\Storage\Location;
use App\Models\Product;

class Rack extends Model
{
    use HasFactory;

    protected $table = 'racks';

    protected $fillable = [
        'rack_image',
        'rack_number',
        'capacity',
    ];

    public function zones(): HasManyThrough  {
        return $this->hasManyThrough(Zone::class, Location::class, 'rack_id','zone_id');
    }

    public function locations(): HasMany {
        return $this->hasMany(Location::class, 'rack_id');
    }

    public function products(): HasMany {
        return $this->hasMany(Product::class, 'rack_id');
    }
}
