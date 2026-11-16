<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Galaxy extends Model
{
    protected $table = 'galaxies';

    protected $primaryKey = 'galaxy_id';

    protected $fillable = [
        'galaxy_name',
        'galaxy_type',
        'distance_mly',
        'redshift',
        'mass_solar',
        'diameter_ly',
        'added_by',
        'verified_by',
    ];

    public $timestamps = false;

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by', 'user_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by', 'user_id');
    }

    public function constellations(): HasMany
    {
        return $this->hasMany(Constellation::class, 'galaxy_id', 'galaxy_id');
    }
}
