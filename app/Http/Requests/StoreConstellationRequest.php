<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConstellationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'constellationName' => ['required', 'string', 'max:100'],
            'galaxyId' => ['required', 'integer', 'exists:galaxies,galaxy_id'],
            'adderId' => ['required', 'integer', 'exists:users,user_id'],
            'verifierId' => ['nullable', 'integer', 'exists:users,user_id'],
        ];
    }
}
