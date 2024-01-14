<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => (string)$this->id,
            "attributes" => [
                'pharmacist_id' => $this->user_id,
                'status' => $this->status,
                'date' => $this->date,
                'paid' => $this->paid,
                'total_price' => $this->total_price,
                'warehouse_id' => $this->warehouse_id,
            ],
            'medicines' => $this->medicines->map(function ($medicine) {
                return [
                    'pivot' => $medicine->pivot,
                    'commercial_name' => $medicine->commercial_name
                ];
            })
        ];
    }
}
