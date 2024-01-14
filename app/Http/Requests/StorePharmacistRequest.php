<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePharmacistRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => ['required' , 'string'],
            'phone_number' => ['required' , 'string' , 'unique:pharmacists,phone_number' , 'regex:/^((09))[0-9]{8}/' ],
            'password' => ['required' , 'min:5' , 'max:80'],
            'location' => ['required' , 'string']
        ];
    }
}
