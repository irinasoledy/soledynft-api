<?php
namespace App\Http\Controllers\Payments\Paynet;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Payments\PaynetRequest;
use App\Http\Controllers\Payments\PaynetResult;
use App\Http\Controllers\Payments\PaynetCode;
use App\Http\Controllers\Payments\PaynetEcomAPI;
use App\Models\CRMOrders;

class Paynet extends Controller
{
	public function index($order)
	{
		// $merchantCode       = '057778';
		// $merchantSecretCode = '20933827-5585-45A5-88CA-3ADEEF570BE2';
		// $user               = '495637';
		// $pass               = '0gifdQtG';

		$merchantCode       = '770792';
		$merchantSecretCode = '8C53C1A8-B882-4E2B-99D2-1E004EE14F4F';
		$user               = '026255';
		$pass               = 'Oev0d6Bf';

		$api = new PaynetEcomAPI($merchantCode,$merchantSecretCode,$user,$pass);
		$prequest = new PaynetRequest();
		$prequest->ExternalID 	=  round(microtime(true) * 1000);
		$prequest->LinkSuccess 	= url('/paynet-success-link/'.$order->id)."?id=$prequest->ExternalID";
		$prequest->LinkCancel 	= url('/paynet-cancel-link/'.$order->id)."?id=$prequest->ExternalID";
		$prequest->Lang = 'ru';

		$orderProducts = [];
		$amount = 0;
		if ($order->orderSubproducts()->count() > 0) {
            foreach ($order->orderSubproducts as $key => $subproductItem) {
                $orderProducts[] = [
				   'LineNo' 	=> $key + 1,
				   'Code' 		=> $subproductItem->code,
				   'Barcode' 	=> $subproductItem->code,
				   'Name' 		=> $subproductItem->subproduct->product->translation->name,
				   'Description' => $subproductItem->subproduct->product->translation->name,
				   'Quantity' 	=> $subproductItem->qty * 100,
				   'UnitPrice' 	=> number_format($subproductItem->subproduct->product->personalPrice->price) * 100
                ];
                $amount += number_format($subproductItem->subproduct->product->personalPrice->price);
            }
        }

        if ($order->orderProducts()->count() > 0) {
            foreach ($order->orderProducts as $key => $productItem) {
                $orderProducts[] = [
				   'LineNo' => $key + 1,
				   'Code' => $productItem->code,
				   'Barcode' => $productItem->code,
				   'Name' => $productItem->product->translation->name,
				   'Description' => $productItem->product->translation->name,
				   'Quantity' => $productItem->qty * 100,
				   'UnitPrice' => number_format((int)$productItem->product->personalPrice->price) * 100
                ];
                $amount += number_format((int) $productItem->product->personalPrice->price);
				// dd($productItem->product->personalPrice->price);
            }
        }

		$prequest->Products = $orderProducts; 	// массив продуктов
		$prequest->Amount = $amount;			// сумма заказа

		$shippingPrice = $order->shipping_price;

		$prequest->Service = array (
				array (
						'Name'		 => 'soledy.com',
						'Description'=> 'soledy.com',
						'Amount'	=> ($shippingPrice * 20.00) * 100,
						'Products'	=> $orderProducts
					)
				);

		// dd($order->details->city);

		$prequest->Customer = array(
				'Code' 		=> $order->details->email,
				'Address' 	=> $order->details->address,
				'Name' 		=> $order->details->contact_name,
				'NameLast'	=> $order->details->contact_name,
				'Country'	=> 'Republica Moldova',
				'City'		=> $order->details->city,
				'Email'		=> $order->details->email,
				'Gsm'		=> $order->details->phone,
				'PhoneNumber'=> $order->details->phone,
		);

		$paymentRegObj = $api->PaymentReg($prequest);

		// dd($paymentRegObj, $amount);
		$form = $paymentRegObj->Data;

		return view('front.'. $this->device .'.dynamic.paynet-redirect', compact('form'));

		echo $paymentRegObj->Data;

		echo "<br>-----------------------------------------------------------------------------------<br>";
		echo "<a href='/psp' > < Back</a><br>";
	}

	public function callBackLink()
	{
		// $merchantCode       = '057778';
		// $merchantSecretCode = '20933827-5585-45A5-88CA-3ADEEF570BE2';
		// $user               = '495637';
		// $pass               = '0gifdQtG';

		$merchantCode       = '770792';
		$merchantSecretCode = '587CB68B-0E42-4B4B-8591-DA00B45FAB67';
		$user               = '026255';
		$pass               = 'Oev0d6Bf';

		$api = new PaynetEcomAPI($merchantCode,$merchantSecretCode,$user,$pass);

		//------------------------ get an input stream -----------------------------
		$paymentInfo = file_get_contents('php://input');
		$paymentObj = json_decode($paymentInfo);
		if (!$paymentObj) {
		    echo "The returning object has not found !";
		    return;
		}
		echo 'Signature of confirm responding: '.apache_request_headers()['Hash'];

		echo "--------------------- Returning object -------------------------<br>";
		print_r($paymentObj);
		echo "--------------------- Check if PAID ---------------------------<br>";
		if($paymentObj->EventType !== 'PAID'){
			echo "NOT SUCCESS";
			return;
		}
		$prequest = new PaynetRequest();
		echo " ExternalId --->  ".$paymentObj->Payment->ExternalId;
		echo "<br>-------------------  check a payment on the pgw site  by merchant id  ----------------<br>";
		$checkObj = $api->PaymentGet($paymentObj->Payment->ExternalId);
		print_r($checkObj);
		if($checkObj->IsOk())
		{
			echo $checkObj->Data[0]['Status'] ;
			echo "<br>".$checkObj->Data[0]['Invoice'] ;
			echo "<br>".$checkObj->Data[0]['Amount'] ;
			//The successfull operation has Status has to be as: 4
			if ($checkObj->Data[0]['Status'] !== 4) {
				echo 'The payment status is not complete. Please wait and try again !!!';
				return;
			}
			//------------- here you can confirm your transaction !
			echo 'The payment has confirmed';
		}
		echo "<br>-----------------------------------------------------------------------------------<br>";
	}

	public function successLink($id)
	{
		$order = CRMOrders::findOrFail($id);

		$paymentController = new PaymentController();
		return $paymentController->orderSuccess($order->id);
	}

	public function cancelLink($id)
	{
		$order = CRMOrders::findOrFail($id);

		$paymentController = new PaymentController();
		return $paymentController->orderFail($order->id);
		// return redirect('/'.$this->lang->lang.'/order/payment/'.$order->id);
	}
}
