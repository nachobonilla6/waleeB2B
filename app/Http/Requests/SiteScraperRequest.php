<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteScraperRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'google_maps_url' => ['required', 'url', 'starts_with:https://www.google.com/maps/'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'google_maps_url.required' => 'La URL de Google Maps es obligatoria',
            'google_maps_url.url' => 'La URL proporcionada no es válida',
            'google_maps_url.starts_with' => 'La URL debe ser un enlace de Google Maps',
        ];
    }
}
