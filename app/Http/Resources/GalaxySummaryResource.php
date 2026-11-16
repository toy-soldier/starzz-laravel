<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalaxySummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'galaxyId' => $this->galaxy_id,
            'galaxyName' => $this->galaxy_name,
        ];
    }
}
