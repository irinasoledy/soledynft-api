<?php

namespace App\Http\Controllers\API;

use App\Factories\ProductOffersFactory;
use App\Http\Requests\OfferGetRequest;
use App\Http\Requests\OfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\FeedBack;
use App\Models\Product;

class OfferController extends ApiController
{
    private $productOffersFactory;


    public function __construct(ProductOffersFactory $productOffersFactory)
    {
        $this->productOffersFactory = $productOffersFactory;
    }

    public function createOffer(OfferRequest $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return response(['message' => 'Language or currency not found'], 500);;
        }

        $product = Product::find($request->get('productId'));

        if ($product) {
            $message = 'New offer from ' . $request->get('userId') . ': ' . $request->get('price') . ' NEAR for ' . $product->translation->name;
            $offer = FeedBack::create([
                'form' => 'make-offer',
                'status' => 'offer',
                'first_name' => $request->get('userId'),
                'subject' => $product->translation->name,
                'message' => $message,
                'additional_1' => $product->id,
                'additional_2' => number_format($request->get('price'), 2, '.', ' '),
            ]);

            return new OfferResource($offer);
        }

        return response(['message' => 'This product not exists'], 404);;
    }

    public function getOffers(OfferGetRequest $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return response(['message' => 'Language or currency not found'], 500);;
        }

        return $this->productOffersFactory->getProductOffers($request->get('productId'));
    }
}