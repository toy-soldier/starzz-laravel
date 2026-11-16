<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Constellation extends Model
{
    protected $table = 'constellations';

    protected $primaryKey = 'constellation_id';

    protected $fillable = [
        'constellation_name',
        'galaxy_id',
        'added_by',
        'verified_by',
    ];

    public $timestamps = false;

    public function galaxy(): BelongsTo
    {
        return $this->belongsTo(Galaxy::class, 'galaxy_id', 'galaxy_id');
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by', 'user_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by', 'user_id');
    }

    public function stars(): HasMany
    {
        return $this->hasMany(Star::class, 'constellation_id', 'constellation_id');
    }
}
