<?php

namespace App\Http\Controllers\Payments\Paynet;


class PaynetResult
{
	public $Code ;
	public $Message ;
	public $Data ;
	public function IsOk()
	{
		return $this->Code === PaynetCode::SUCCESS;
	}
}
