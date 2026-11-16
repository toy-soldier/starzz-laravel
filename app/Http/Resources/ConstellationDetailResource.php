<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConstellationDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'constellationId' => $this->constellation_id,
            'constellationName' => $this->constellation_name,
            'galaxy' => new GalaxySummaryResource($this->galaxy),
            'addedBy' => new UserSummaryResource($this->addedBy),
            'verifiedBy' => new UserSummaryResource($this->verifiedBy),
        ];
    }
}
