<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Zone;
use App\Models\Rack;
use App\Models\SKU;

class StorageLocation extends Model
{
    use HasFactory;

    protected $table = 'storage_locations';

    protected $fillable = [
        'zone_id',
        'rack_id',
    ];

    public function zone(): BelongsTo {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function rack(): BelongsTo {
        return $this->belongsTo(Rack::class, 'rack_id');
    }
}
