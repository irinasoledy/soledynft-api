<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Models\Lang;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Cart;


class CheckoutController extends ApiController
{
    public function __construct(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }
    }

    private function returnCartItems($userId)
    {
        $data['products'] = Cart::with(['product.mainPrice', 'product.personalPrice', 'product.translation', 'product.mainImage', 'product.category'])
                                      ->where('user_id', $userId)
                                      ->where('product_id', '!=', null)
                                      ->where('subproduct_id',  0)
                                      ->orderBy('id', 'desc')
                                      ->get();

        $data['subproducts'] = Cart::with(['subproduct.price', 'subproduct.product.mainPrice', 'subproduct.product.personalPrice', 'subproduct.product.translation', 'subproduct.product.mainImage', 'subproduct.product.category', 'subproduct.parameterValue.translation'])
                                    ->where('user_id', $userId)
                                    ->where('subproduct_id', '!=', 0)
                                    ->orderBy('id', 'desc')
                                    ->get();
        return $data;
    }

    public function getCart(Request $request)
    {
        $data = $this->returnCartItems($request->get('userId'));

        return $this->respond($data);
    }

    public function setCart(Request $request)
    {
        $findCart = Cart::where('user_id', $request->get('userId'))->first();

        if (is_null($findCart)) {
            Cart::create([
                'user_id' => $request->get('userId'),
                'product_id' => $request->get('productId'),
                'subproduct_id' => $request->get('subproductId') ?? 0,
                'parent_id' => $request->get('setId') ?? 0,
                'qty' => 1,
            ]);
        }else {
            $findProductCart = Cart::where('user_id', $request->get('userId'))
                                    ->where('product_id', $request->get('productId'))
                                    ->where('subproduct_id', $request->get('subproductId') ?? 0)
                                    ->first();

            if (is_null($findProductCart)) {
                Cart::create([
                    'user_id' => $request->get('userId'),
                    'product_id' => $request->get('productId'),
                    'subproduct_id' => $request->get('subproductId') ?? 0,
                    'parent_id' => $request->get('setId') ?? 0,
                    'qty' => 1,
                ]);
            }
        }

        $data = $this->returnCartItems($request->get('userId'));

        return $this->respond($data);
    }

    public function deleteCart(Request $request)
    {
        Cart::where('id', $request->get('id'))->delete();

        $data = $this->returnCartItems($request->get('userId'));

        return $this->respond($data);
    }

    public function changeQtyCart(Request $request)
    {
        Cart::where('id', $request->get('cartId'))->update([
            'qty' => $request->get('qty')
        ]);

        $data = $this->returnCartItems($request->get('userId'));

        return $this->respond($data);
    }

    public function deleteAllCarts(Request $request)
    {
        Cart::where('user_id', $request->get('userId'))->delete();

        $data = $this->returnCartItems($request->get('userId'));

        return $this->respond($data);
    }
}
