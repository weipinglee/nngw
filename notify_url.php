<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
define('IN_DOUCO', true);
require_once(dirname(__FILE__) ."/direct_pay/alipay.config.php");
require_once(dirname(__FILE__) ."/direct_pay/lib/alipay_notify.class.php");
require_once(dirname(__FILE__) . '/include/init.php');

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功

    if($_GET['trade_status'] == 'TRADE_SUCCESS' OR $_GET['trade_status'] == 'TRADE_FINISHED'){//操作成功
        $data = array();
        $order_no = $_GET['out_trade_no'];
        $order_status = $dou->get_one("SELECT order_trade_status FROM " . $dou->table('order') . " WHERE order_no = '".$order_no."'" );

        if($order_status == 0) {
            $data['order_trade_no'] = $_GET['trade_no'];
            $data['order_trade_status'] = 1;
            $data['order_notify_time'] = date('Y-m-d H:i:s');
            $dou->data($data);
            $dou->setTable('order');
            $dou->update('order_no = "' . $order_no . '"');
        }

    }
    else echo '交易失败';
        
	echo "success";		//请不要修改或删除
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";
}
?>