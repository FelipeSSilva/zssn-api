<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TradeSurvivorsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'resourceSurvivorOffer.Water' => 'integer|min:1|nullable',
            'resourceSurvivorOffer.Food' => 'integer|min:1|nullable',
            'resourceSurvivorOffer.Medication' => 'integer|min:1|nullable',
            'resourceSurvivorOffer.Ammunition' => 'integer|min:1|nullable',
            'resourceSurvivorAccept.Water' => 'integer|min:1|nullable',
            'resourceSurvivorAccept.Food' => 'integer|min:1|nullable',
            'resourceSurvivorAccept.Medication' => 'integer|min:1|nullable',
            'resourceSurvivorAccept.Ammunition' => 'integer|min:1|nullable',
        ];
    }
}
