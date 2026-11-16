<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'starName' => ['required', 'string', 'max:100'],
            'starType' => ['required', 'string', 'max:100'],
            'constellationId' => ['required', 'integer', 'exists:constellations,constellation_id'],
            'rightAscension' => ['required', 'integer', 'min:' . config('starzz.int_min'), 'max:' . config('starzz.int_max')],
            'declination' => ['required', 'integer', 'min:' . config('starzz.int_min'), 'max:' . config('starzz.int_max')],
            'apparentMagnitude' => ['required', 'integer', 'min:' . config('starzz.int_min'), 'max:' . config('starzz.int_max')],
            'spectralType' => ['required', 'string', 'max:5'],
            'adderId' => ['required', 'integer', 'exists:users,user_id'],
            'verifierId' => ['nullable', 'integer', 'exists:users,user_id'],
        ];
    }
}
