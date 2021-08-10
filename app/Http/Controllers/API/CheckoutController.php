<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Models\Lang;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Cart;


class CheckoutController extends ApiController
{
    public function getCart(Request $request)
    {
        $data['products'] = Cart::with(['product.mainPrice', 'product.personalPrice', 'product.translation', 'product.mainImage', 'product.category'])
                                      ->where('user_id', $request->get('userId'))
                                      ->where('product_id', '!=', null)
                                      ->where('subproduct_id',  0)
                                      ->orderBy('id', 'desc')
                                      ->where('active', 1)
                                      ->get();

        $data['subproducts'] = Cart::with(['subproduct.price', 'subproduct.product.mainPrice', 'subproduct.product.personalPrice', 'subproduct.product.translation', 'subproduct.product.mainImage', 'subproduct.product.category', 'subproduct.parameterValue.translation'])
                                    ->where('user_id', $request->get('userId'))
                                    ->where('subproduct_id', '!=', 0)
                                    ->orderBy('id', 'desc')
                                    ->where('active', 1)
                                    ->get();

        return $this->respond($data);
    }

    public function setCart(Request $request)
    {
        dd("fd");
        return $request->all();
    }
}
