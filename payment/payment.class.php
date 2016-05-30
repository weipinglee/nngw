<?php

/**
 * Class payment 包含不同的支付方式
 */
class payment
{

	/**
	 *产生一个实例
	 */
	static public function requirePayMethod($payment_name, $param)
	{
		//$param = $param;
		require $payment_name . '.php';
	}

}