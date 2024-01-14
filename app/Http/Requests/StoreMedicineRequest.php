<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicineRequest extends FormRequest
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
            'scientific_name' => ['required' , 'max:255'  , 'string'] ,
            'commercial_name' => ['required' , 'max:255' , 'string'] ,
            'price' => ['required' ,  'integer'] ,

            'company_id' => [
                'required' ,
                'exists:companies,id' ,
                Rule::unique('medicines')->where(function ($query) {
                    return $query->where('company_id', $this->company_id)
                                 ->where('medicines.commercial_name', $this->scientific_name)
                                 ->where('medicines.scientific_name', $this->commercial_name);
                }),
            ],
            'category_id' => ['required' , 'exists:categories,id']
        ];
    }
}
