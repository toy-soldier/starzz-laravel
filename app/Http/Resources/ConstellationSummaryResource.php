<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConstellationSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'constellationId' => $this->constellation_id,
            'constellationName' => $this->constellation_name,
        ];
    }
}
