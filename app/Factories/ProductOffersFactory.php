<?php

namespace App\Factories;

use App\Http\Resources\OfferResource;
use App\Models\FeedBack;

class ProductOffersFactory
{
    public function getProductOffers($productId)
    {
        $offers = FeedBack::select('id', 'first_name', 'message', 'subject', 'additional_1', 'additional_2', 'created_at')
            ->where('additional_1', $productId)->get();

        return OfferResource::collection($offers);
    }
}