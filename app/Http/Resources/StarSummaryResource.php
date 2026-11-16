<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StarSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'starId' => $this->star_id,
            'starName' => $this->star_name,
        ];
    }
}
