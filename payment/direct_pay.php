<?php
header("Content-type: text/html; charset=utf-8");
require_once(dirname(dirname(__FILE__)) ."/direct_pay/alipay.config.php");
require_once(dirname(dirname(__FILE__)) ."/direct_pay/lib/alipay_submit.class.php");


$params = array(
	"service" => "create_direct_pay_by_user",
	"partner" => trim($alipay_config['partner']),
	"seller_id" => trim($alipay_config['partner']),
	"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);
$params = array_merge($params,$param);


$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($params,"get", "页面跳转...");
echo $html_text;
?>
