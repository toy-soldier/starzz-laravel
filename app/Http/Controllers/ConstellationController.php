<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreConstellationRequest;
use App\Http\Resources\ConstellationDetailResource;
use App\Http\Resources\ConstellationSummaryResource;
use App\Models\Constellation;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ConstellationController extends Controller
{
    public function getConstellationList(): AnonymousResourceCollection
    {
        return ConstellationSummaryResource::collection(
            Constellation::all()
        );
    }

    public function getConstellation(int $id): ConstellationDetailResource
    {
        return new ConstellationDetailResource(
            Constellation::with(['galaxy', 'addedBy', 'verifiedBy'])
                ->findOrFail($id)
        );
    }

    public function registerConstellation(StoreConstellationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $constellation = Constellation::create([
            'constellation_name' => $data['constellationName'],
            'galaxy_id' => $data['galaxyId'],
            'added_by' => $data['adderId'],
            'verified_by' => $data['verifierId'] ?? null,
        ]);

        return $this->getConstellation($constellation->constellation_id)
            ->response()
            ->setStatusCode(201);
    }

    public function updateConstellation(StoreConstellationRequest $request, int $id): ConstellationDetailResource
    {
        $data = $request->validated();

        $constellation = Constellation::findOrFail($id);

        $constellation->update([
            'constellation_name' => $data['constellationName'],
            'galaxy_id' => $data['galaxyId'],
            'added_by' => $data['adderId'],
            'verified_by' => $data['verifierId'] ?? null,
        ]);

        return $this->getConstellation($id);
    }

    public function deleteConstellation(int $id): Response
    {
        $constellation = Constellation::findOrFail($id);

        DB::transaction(function () use ($constellation) {
            $constellation->stars()->delete();
            $constellation->delete();
        });

        return response()->noContent();
    }
}
