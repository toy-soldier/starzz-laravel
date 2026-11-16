<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreStarRequest;
use App\Http\Resources\StarDetailResource;
use App\Http\Resources\StarSummaryResource;
use App\Models\Star;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class StarController extends Controller
{
    public function getStarList(): AnonymousResourceCollection
    {
        return StarSummaryResource::collection(
            Star::all()
        );
    }

    public function getStar(int $id): StarDetailResource
    {
        return new StarDetailResource(
            Star::with(['constellation', 'addedBy', 'verifiedBy'])
                ->findOrFail($id)
        );
    }

    public function registerStar(StoreStarRequest $request): JsonResponse
    {
        $data = $request->validated();

        $star = Star::create([
            'star_name' => $data['starName'],
            'star_type' => $data['starType'],
            'constellation_id' => $data['constellationId'],
            'right_ascension' => $data['rightAscension'],
            'declination' => $data['declination'],
            'apparent_magnitude' => $data['apparentMagnitude'],
            'spectral_type' => $data['spectralType'],
            'added_by' => $data['adderId'],
            'verified_by' => $data['verifierId'] ?? null,
        ]);

        return $this->getStar($star->star_id)
            ->response()
            ->setStatusCode(201);
    }

    public function updateStar(StoreStarRequest $request, int $id): StarDetailResource
    {
        $data = $request->validated();

        $star = Star::findOrFail($id);

        $star->update([
            'star_name' => $data['starName'],
            'star_type' => $data['starType'],
            'constellation_id' => $data['constellationId'],
            'right_ascension' => $data['rightAscension'],
            'declination' => $data['declination'],
            'apparent_magnitude' => $data['apparentMagnitude'],
            'spectral_type' => $data['spectralType'],
            'added_by' => $data['adderId'],
            'verified_by' => $data['verifierId'] ?? null,
        ]);

        return $this->getStar($id);
    }

    public function deleteStar(int $id): Response
    {
        Star::findOrFail($id)->delete();

        return response()->noContent();
    }
}
