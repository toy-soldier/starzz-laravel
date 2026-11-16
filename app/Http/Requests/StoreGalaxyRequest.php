<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalaxyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'galaxyName' => ['required', 'string', 'max:100'],
            'galaxyType' => ['required', 'string', 'max:100'],
            'distanceMly' => ['required', 'integer', 'min:0', 'max:' . config('starzz.int_max')],
            'redshift' => ['required', 'integer', 'min:' . config('starzz.int_min'), 'max:' . config('starzz.int_max')],
            'massSolar' => ['required', 'integer', 'min:0', 'max:' . config('starzz.int_max')],
            'diameterLy' => ['required', 'integer', 'min:0', 'max:' . config('starzz.int_max')],
            'adderId' => ['required', 'integer', 'exists:users,user_id'],
            'verifierId' => ['nullable', 'integer', 'exists:users,user_id'],
        ];
    }
}
