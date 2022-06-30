<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => (int)$this->id,
            'account_id' => (string)$this->first_name,
            'message' => (string)$this->message,
            'product_id' => (int)$this->additional_1,
            'product_name' => (string)$this->subject,
            'price' => (int)$this->additional_2,
            'price_decimals' => (string)$this->additional_2,
            'created_at' => (string)$this->created_at,
            'accepted' => (bool)$this->additional_3
        ];
    }
}
