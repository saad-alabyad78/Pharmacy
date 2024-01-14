<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateMedicineRequest extends FormRequest
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
            'commercial_name' => ['required' , 'max:255'  , 'string'] ,
            'max_amount' => ['required' , 'integer'] ,
            'price' => ['required' ,  'integer'] ,

            'company_id' => [
                'required' ,
                'exists:companies,id' ,
                function ($attribute, $value, $fail) {
                    $exists = DB::table('medicines')
                        ->where('company_id', $this->company_id)
                        ->where('commercial_name', $this->commercial_name)
                        ->where('scientific_name', $this->scientific_name)
                        ->where('id', '!=', $this->id) // Ignore the current medicine
                        ->exists();
                   
                    if ($exists) {
                        $fail('The combination of company_id, commercial_name, and scientific_name already exists.');
                    }}
            ],
            'category_id' => ['required' , 'exists:categories,id'] 
        ];
    }
}
