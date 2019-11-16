<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

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

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
