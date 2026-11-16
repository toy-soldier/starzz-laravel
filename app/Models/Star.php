<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Star extends Model
{
    protected $table = 'stars';

    protected $primaryKey = 'star_id';

    protected $fillable = [
        'star_name',
        'star_type',
        'constellation_id',
        'right_ascension',
        'declination',
        'apparent_magnitude',
        'spectral_type',
        'added_by',
        'verified_by',
    ];

    public $timestamps = false;

    public function constellation(): BelongsTo
    {
        return $this->belongsTo(Constellation::class, 'constellation_id', 'constellation_id');
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by', 'user_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by', 'user_id');
    }
}
