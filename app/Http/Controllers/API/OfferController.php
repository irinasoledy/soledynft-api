<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\OfferRequest;
use App\Models\FeedBack;

class OfferController
{
    public function createOffer(OfferRequest $request)
    {
        $message = 'New offer from ' . $request->get('userId') . '. ' . $request->get('price') . ' NEAR for ' . $request->get('productName');

        $offer = FeedBack::create([
            'form' => 'make-offer',
            'status' => 'offer',
            'first_name' => $request->get('userId'),
            'message' => $message,
            'additional_1' => $request->get('productId'),
            'additional_2' => $request->get('price'),
        ]);

        return $offer;
    }
}