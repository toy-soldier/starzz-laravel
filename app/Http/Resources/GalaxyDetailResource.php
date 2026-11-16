<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalaxyDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'galaxyId' => $this->galaxy_id,
            'galaxyName' => $this->galaxy_name,
            'galaxyType' => $this->galaxy_type,
            'distanceMly' => $this->distance_mly,
            'redshift' => $this->redshift,
            'massSolar' => $this->mass_solar,
            'diameterLy' => $this->diameter_ly,
            'addedBy' => new UserSummaryResource($this->addedBy),
            'verifiedBy' => new UserSummaryResource($this->verifiedBy),
        ];
    }
}
