<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */
define('IN_DOUCO', true);
require_once(dirname(__FILE__) ."/direct_pay/alipay.config.php");
require_once(dirname(__FILE__) ."/direct_pay/lib/alipay_notify.class.php");
require_once(dirname(__FILE__) . '/include/init.php');

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
//$smarty->display('return_url.dwt');exit;
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

    if($_GET['trade_status'] == 'TRADE_SUCCESS' OR $_GET['trade_status'] == 'TRADE_FINISHED'){//操作成功
        $data = array();
        $order_no = $_GET['out_trade_no'];
        $order_status = $dou->get_one("SELECT order_trade_status FROM " . $dou->table('order') . " WHERE order_no = '".$order_no."'" );

        $query = $dou->select($dou->table('order'), '*', '`order_no` = \'' . $order_no . '\'');
        $order_detail = $dou->fetch_assoc($query);
        if($order_detail['order_trade_status'] == 0){
            $data['order_trade_no'] = $_GET['trade_no'];
            $data['order_trade_status'] = 1;
            $data['order_notify_time'] = date('Y-m-d H:i:s');
            $dou->data($data);
            $dou->setTable('order');
            $dou->update('order_no = "'.$order_no.'"');

        }

        $smarty->assign('order',$order_detail);
        $smarty->display('return_url.dwt');

    }
    else {
        $smarty->assign('title','支付失败');

        $smarty->display('error.dwt');
    }
}
else {
    $smarty->assign('title','支付失败');

    $smarty->display('error.dwt');
}
?>
