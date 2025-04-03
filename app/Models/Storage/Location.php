<?php

namespace App\Models\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Storage\Zone;
use App\Models\Storage\Rack;

class Location extends Model
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
