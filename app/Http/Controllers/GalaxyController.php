<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreGalaxyRequest;
use App\Http\Resources\GalaxyDetailResource;
use App\Http\Resources\GalaxySummaryResource;
use App\Models\Galaxy;
use App\Models\Star;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GalaxyController extends Controller
{
    public function getGalaxyList(): AnonymousResourceCollection
    {
        return GalaxySummaryResource::collection(
            Galaxy::all()
        );
    }

    public function getGalaxy(int $id): GalaxyDetailResource
    {
        return new GalaxyDetailResource(
            Galaxy::with(['addedBy', 'verifiedBy'])
                ->findOrFail($id)
        );
    }

    public function registerGalaxy(StoreGalaxyRequest $request): JsonResponse
    {
        $data = $request->validated();

        $galaxy = Galaxy::create([
            'galaxy_name' => $data['galaxyName'],
            'galaxy_type' => $data['galaxyType'],
            'distance_mly' => $data['distanceMly'],
            'redshift' => $data['redshift'],
            'mass_solar' => $data['massSolar'],
            'diameter_ly' => $data['diameterLy'],
            'added_by' => $data['adderId'],
            'verified_by' => $data['verifierId'] ?? null,
        ]);

        return $this->getGalaxy($galaxy->galaxy_id)
            ->response()
            ->setStatusCode(201);
    }

    public function updateGalaxy(StoreGalaxyRequest $request, int $id): GalaxyDetailResource
    {
        $data = $request->validated();

        $galaxy = Galaxy::findOrFail($id);

        $galaxy->update([
            'galaxy_name' => $data['galaxyName'],
            'galaxy_type' => $data['galaxyType'],
            'distance_mly' => $data['distanceMly'],
            'redshift' => $data['redshift'],
            'mass_solar' => $data['massSolar'],
            'diameter_ly' => $data['diameterLy'],
            'added_by' => $data['adderId'],
            'verified_by' => $data['verifierId'] ?? null,
        ]);

        return $this->getGalaxy($id);
    }

    public function deleteGalaxy(int $id): Response
    {
        $galaxy = Galaxy::findOrFail($id);

        DB::transaction(function () use ($galaxy) {
            Star::whereIn(
                'constellation_id',
                $galaxy->constellations()->select('constellation_id')
            )->delete();

            $galaxy->constellations()->delete();
            $galaxy->delete();
        });

        return response()->noContent();
    }
}
