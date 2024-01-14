<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'scientific_name' => $this->scientific_name ,
            'commercial_name' => $this->commercial_name ,
            'price' => $this->price ,

             'category' => new CategoryResource($this->category) ,
             'company' => new CompanyResource($this->company) ,


        ];
    }
}
