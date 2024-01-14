<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PharmacistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id ,
            'attribute' => [
                'name' => $this->name ,
                'phone' => $this->phone , 
                'password' => $this->password ,
                'location' => $this->location ,
            ] ,
            'favorites' => [
                MedicineResource::collection($this->favorites) ,
            ] ,
            'orders' => [
                $this->orders ,
            ] ,
        ];
    }
}
