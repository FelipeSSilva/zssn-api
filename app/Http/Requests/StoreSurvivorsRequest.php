<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StoreSurvivorsRequest extends FormRequest
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
        $rules = [
            'name' => 'string|required|max:255',
            'age' => 'integer|required|min:0',
            'gender' => 'string|required|in:Male,Female',
            'latitude' => 'numeric|required',
            'longitude' => 'numeric|required',
            'resources.Water' => 'integer|min:0|nullable',
            'resources.Food' => 'integer|min:0|nullable',
            'resources.Medication' => 'integer|min:0|nullable',
            'resources.Ammunition' => 'integer|min:0|nullable',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'age.required' => 'Age is required',
            'gender.required' => 'Gender is required',
            'latitude.required' => 'Latitude is required',
            'longitude.required' => 'Longitude is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}