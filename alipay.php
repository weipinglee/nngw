<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
ini_set('display_errors','On');
/* *
 * 功能：即时到账交易接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */
define('IN_DOUCO', true);
require_once(dirname(__FILE__) ."/include/tool.class.php");
require_once(dirname(__FILE__) ."/include/filter_class.php");
require_once(dirname(__FILE__) ."/payment/payment.class.php");
require (dirname(__FILE__) . '/include/init.php');
define('WEB_ROOT',Tool::getHttpHost());

//将订单信息写入数据库
$data = array();
$data['order_no'] = Tool::getOrderNo();

$data['product_id'] = IFilter::act($_POST['product_id'],'int');
$data['product_num'] = IFilter::act($_POST['product_num'],'int');
$data['buyer_company']  = IFilter::act($_POST['buyer_company']);
$data['buyer_name']  = IFilter::act($_POST['buyer_name']);
$data['buyer_phone'] = IFilter::act($_POST['buyer_phone']);
$data['buyer_province'] = IFilter::act($_POST['province']);
$data['buyer_city'] = IFilter::act($_POST['city']);
$data['buyer_area'] = IFilter::act($_POST['area']);
$data['buyer_address'] = IFilter::act($_POST['buyer_address']);

$data['invo_type'] = IFilter::act($_POST['invo_type'],'int');
$data['invo_title'] = IFilter::act($_POST['invo_title']);
$data['invo_content'] = IFilter::act($_POST['invo_content']);
$data['invo_taxno'] = IFilter::act($_POST['invo_taxno']);
$data['invo_address'] = IFilter::act($_POST['invo_address']);
$data['invo_phone'] = IFilter::act($_POST['invo_phone']);
$data['invo_bank'] = IFilter::act($_POST['invo_bank']);
$data['invo_account'] = IFilter::act($_POST['invo_account']);
$data['invo_company'] = IFilter::act($_POST['invo_company']);

$data['order_create_time'] = date('Y-m-d H:i:s');
//计算订单总价
$sql = "SELECT * FROM " . $dou->table('product') . " WHERE id = ".$data['product_id'];
$query = $dou->query($sql);
$product = $dou->fetch_assoc($query);
$price = $product['price'];
$data['product_name'] = $product['name'];
$data['order_total_fee'] = floatval($price * $data['product_num']);
if($data['order_total_fee']==0 || $product['type']==0)return false;
$dou->data($data);
$dou->setTable('order');
if(!$dou->add()){
	$smarty->assign('title','订单提交失败');
	$smarty->assign('product_id',$data['product_id']);
	$smarty->display('error.dwt');
	return false;
};


/**************************请求参数**************************/

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = WEB_ROOT."/notify_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = WEB_ROOT."/return_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //商户订单号
       // $out_trade_no = $_POST['WIDout_trade_no'];
		$out_trade_no = $data['order_no'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称(表单获取）
        $subject = $data['product_name'];
        //必填

        //付款金额(表单获取）
        $total_fee = $data['order_total_fee'];
        //必填

        //订单描述

       // $body = $_POST['WIDbody'];
        //商品展示地址
        $show_url = WEB_ROOT.'/product.php?id='.$data['product_id'];
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1


/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		//"service" => "create_direct_pay_by_user",
		//"partner" => trim($alipay_config['partner']),
		//"seller_id" => trim($alipay_config['partner']),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"total_fee"	=> $total_fee,
		//"body"	=> $body,
		"show_url"	=> $show_url,
		//"anti_phishing_key"	=> $anti_phishing_key,
		//"exter_invoke_ip"	=> $exter_invoke_ip,
		//"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);
if($dou->is_mobile())
	payment::requirePayMethod('wap_pay',$parameter);
else
	payment::requirePayMethod('direct_pay',$parameter);

?>
