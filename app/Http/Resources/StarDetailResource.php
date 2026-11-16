<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StarDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'starId' => $this->star_id,
            'starName' => $this->star_name,
            'starType' => $this->star_type,
            'constellation' => new ConstellationSummaryResource($this->constellation),
            'rightAscension' => $this->right_ascension,
            'declination' => $this->declination,
            'apparentMagnitude' => $this->apparent_magnitude,
            'spectralType' => $this->spectral_type,
            'addedBy' => new UserSummaryResource($this->addedBy),
            'verifiedBy' => new UserSummaryResource($this->verifiedBy),
        ];
    }
}
