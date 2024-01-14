<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required' , 'in:pending,on_its_way,completed' , 'string'] ,
            'date' => ['required' , 'date'] ,
            'paid' => ['required' , 'boolean'] ,
            'total_price' => ['required' , 'integer'],
            
            'medicines.*.id' => ['required' , 'exist:medicines,id'],
            'medicines.*.total_amount' => ['required' , 'exist:medicines,total_amount'],
        ];
    }
}
