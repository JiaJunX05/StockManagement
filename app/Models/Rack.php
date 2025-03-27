<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Storack;
use App\Models\Zone;
use App\Models\SKU;

class Rack extends Model
{
    use HasFactory;

    protected $table = 'racks';

    protected $fillable = [
        'rack_number',
    ];

    public function zones(): HasManyThrough  {
        return $this->hasManyThrough(Zone::class, Storack::class, 'rack_id','zone_id');
    }

    public function storageLocations(): HasMany {
        return $this->hasMany(StorageLocation::class, 'rack_id');
    }

    public function skus(): HasMany {
        return $this->hasMany(SKU::class);
    }
}
