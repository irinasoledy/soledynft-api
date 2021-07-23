<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Notifications\LogsHandler;
use App\Base as Model;
use App\Models\SubProduct;
use App\Models\Product;
use App\Models\Set;
use App\Models\WarehousesStock;


class Frisbo extends Controller
{
    private static $token = false;
    private static $client = false;

    public function __construct()
    {
        parent::__construct();

        if (!self::$token) {
            $this->authorization();
        }
    }

    /**
     * Login in Frisbo System
     */
    public function authorization()
    {
        try {
            $loginUrl = "https://api.frisbo.ro/v1/auth/login";
            self::$client = new Client();

            $request = self::$client->post($loginUrl, [
                "form_params" => [
                        "email"     =>  "itmalles@gmail.com",
                        "password"  =>  "ItMallFrisbo2019",
                    ]
                ]);

            $response = json_decode($request->getBody()->getContents());
            self::$token = $response->access_token;
        } catch (\Exception $e) {
            $problem = "Frisbo authorization error.";
            LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
        }
    }

    /**
     * Reserver products
     */
    public function reserveProducts($order, $payment, $amount)
    {
        $products  = [];
        $channelId = 315;

        if ($order->orderSubproducts()->count() > 0) {
            foreach ($order->orderSubproducts as $key => $subproductItem) {
                $products[] = [
                    "sku" => $subproductItem->subproduct->product->code,
                    "name" => $subproductItem->subproduct->product->translation->name,
                    "price" => $subproductItem->set_id == 0 ? $subproductItem->subproduct->product->personalPrice->price : $subproductItem->subproduct->product->personalPrice->set_price,
                    "quantity" => $subproductItem->qty,
                    "vat" => 0,
                    "discount" => $subproductItem->subproduct->product->discount
                ];
            }
        }

        if ($order->orderProducts()->count() > 0) {
            foreach ($order->orderProducts as $key => $productItem) {
                $products[] = [
                    "sku" => $productItem->product->code,
                    "name" => $productItem->product->translation->name,
                    "price" => $productItem->set_id == 0 ? $productItem->product->personalPrice->price : $productItem->product->personalPrice->set_price,
                    "quantity" => $productItem->qty,
                    "vat" => 0,
                    "discount" => $productItem->product->discount
                ];
            }
        }

        if ($payment == "cash") $channelId = 527;
        $this->createOrderFrisbo($order, $products, $amount, $channelId);
    }

    /**
     *  Create and Send order to Frisbo
     */
    public function createOrderFrisbo($order, $products, $amount, $channelId)
    {
        if ($channelId == 527) {
            $cashOnDelivery = 1;
        }else{
            $cashOnDelivery = 0;
        }

        $url = "https://api.frisbo.ro/v1/organizations/183/orders";
        $orderReference = $order->order_hash.uniqid();
        $token = self::$token;
        try {
            $request = self::$client->post($url, [
                "headers" => [
                    "Authorization" =>  "Bearer {$token}",
                    "Content-Type" => "application/json"
                ],
                "json" => [
                    "order_reference"     => $orderReference,
                    "organization_id"     => 183,
                    "channel_id"          => $channelId,
                    "warehouse_id"        => 282,
                    "status"              => "open",
                    "reason_status"       => null,
                    "ordered_date"        => null,
                    "delivery_date"       => null,
                    "returned_date"       => null,
                    "canceled_date"       => null,
                    "notes"               => "",
                    "shipped_with"        => "Unknown",
                    "shipped_date"        => null,
                    "preferred_delivery_time" => null,
                    "shipping_customer"   => [
                        "email"         => $order->details->email,
                        "first_name"    => $order->details->contact_name,
                        "last_name"     => "---",
                        "phone"         => "+". $order->details->code ." ". $order->details->phone
                    ],
                    "shipping_address" => [
                        "street"    => $order->details->address,
                        "city"      => $order->details->city,
                        "county"    => $order->details->region ?? "Unknown",
                        "country"   => $order->details->country,
                        "zip"       => $order->details->zip
                    ],
                    "billing_customer" => [
                        "email"     => $order->details->email,
                        "first_name" => $order->details->contact_name,
                        "last_name" => $order->details->contact_name,
                        "phone"     => "+". $order->details->code ." ". $order->details->phone,
                        "trade_register_registration_number" =>"2063080",
                        "vat_registration_number" =>"J27/1037/1991"
                    ],
                    "billing_address" => [
                        "street"    => $order->details->address,
                        "city"      => $order->details->city,
                        "county"    => $order->details->region ?? "Unknown",
                        "country"   => $order->details->country,
                        "zip"       => $order->details->zip
                    ],
                    "discount" => $amount['personal']['discount'],
                    "transport_tax" => $amount['personal']['shipping'],
                    "cash_on_delivery" => $cashOnDelivery,
                    "products" => $products
                ]
            ]);

            $response = json_decode($request->getBody()->getContents());
            $order->update([
                "order_reference" => $response->order_id,
                "frisbo_reference" => $orderReference,
            ]);

        } catch (\Exception $e) {
            $problem = "Frisbo create order error.";
            LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
        }
    }

    /**
     * Set invoiced status
     */
    public function setOrderInvocedStatus($orderId)
    {
        try {
            $url = "https://api.frisbo.ro/v1/organizations/183/orders/".$orderId."/process";

            $token = self::$token;

            $reponse = self::$client->post($url,[
                'headers' => [
                        'Authorization' =>  "Bearer {$token}",
                    ]
               ]);
        } catch (\Exception $e) {
            $problem = "Frisbo set invoiced status error.";
            LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
        }
    }

    /**
     * Set cancel status
     */
    public function setOrderCanceledStatus($orderId)
    {
        try {
            $url = "https://api.frisbo.ro/v1/organizations/183/orders/". $orderId;

            $token = self::$token;

            $reponse = self::$client->delete($url,[
                'headers' => [
                        'Authorization' =>  "Bearer {$token}",
                    ]
               ]);
        } catch (\Exception $e) {
            $problem = "Frisbo set canceled status error.";
            LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
        }
    }

    /**
     * Synchronize Products Stocks
     */
    public function synchronizeStocks($page = 0)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);

        $url = "https://api.frisbo.ro/v1/organizations/183/products?page=".$page;
        $token = self::$token;

        $request = self::$client->get($url, [
            'headers' => [
                    'Authorization' =>  "Bearer {$token}"
                ]
            ]);

        $response = json_decode($request->getBody()->getContents());
        $warehouseID = 1;

        $i = 0;
        if ($response->data) {
            foreach ($response->data as $key => $responseProduct) {
                if (count($responseProduct->storage) > 0) {
                    $subproduct = SubProduct::where('code', $responseProduct->sku)->first();
                    if (!is_null($subproduct)) {
                        WarehousesStock::where('warehouse_id', $warehouseID)
                                        ->where('subproduct_id', $subproduct->id)
                                        ->update([
                                            'stock' => $responseProduct->storage[0]->available_stock,
                                        ]);
                    }else{
                        $product = Product::where('code', $responseProduct->sku)->first();
                        if (!is_null($product)) {
                            WarehousesStock::where('warehouse_id', $warehouseID)
                                            ->where('subproduct_id', null)
                                            ->where('product_id', $product->id)
                                            ->update([
                                                'stock' => $responseProduct->storage[0]->available_stock,
                                            ]);
                        }
                    }
                    $i++;
                }else{
                    $subproduct = SubProduct::where('code', $responseProduct->sku)->first();
                    if (!is_null($subproduct)) {
                        WarehousesStock::where('warehouse_id', $warehouseID)
                                        ->where('subproduct_id', $subproduct->id)
                                        ->update([
                                            'stock' => 0,
                                        ]);
                    }else{
                        $product = Product::where('code', $responseProduct->sku)->first();
                        if (!is_null($product)) {
                            WarehousesStock::where('warehouse_id', $warehouseID)
                                            ->where('product_id', $product->id)
                                            ->where('subproduct_id', null)
                                            ->update([
                                                'stock' => 0,
                                            ]);
                        }
                    }
                }
            }
        }

        if ($page == 0) {
            try {
                $this->updateSetStocks();
            } catch (\Exception $e) {
                $problem = "Update Set Stocks Error.";
                LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
            }
        }

        if (count($response->data) == 100) {
            $page = $page + 1;
            $this->synchronizeStocks($page);
        }
    }

    public function updateSetStocks()
    {
        $sets = Set::get();
        $outOfStock = 0;

        foreach ($sets as $key => $set) {
            if ($set->products->count() > 0) {
                foreach ($set->products as $key => $product) {
                    if ($set->gift_product_id !== $product->id) {
                        if ($product->warehouse->stock == 0) {
                            $outOfStock++;
                        }
                    }
                }
            }
            if ($outOfStock == ($set->products->count() - 1)) {
                $set->update(["stock" => 0]);
            }else{
                $set->update(["stock" => 1]);
            }
        }
    }

}
