<?php
/**

 */
define('IN_DOUCO', true);

require (dirname(__FILE__) . '/include/init.php');

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'order');

/**
 * +----------------------------------------------------------
 * 分类列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['order']);

    $where = ' WHERE is_del = 0';
    $page = $check->is_number($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $page_url = 'order.php' ;
    $limit = $dou->pager('order', 15, $page, $page_url, $where, $get);

    $sql = "SELECT id, order_no, product_id,product_name,product_num,buyer_name,buyer_phone,buyer_address,order_total_fee,
order_trade_status,order_create_time FROM " . $dou->table('order') . $where . " ORDER BY id DESC" . $limit;
    $query = $dou->query($sql);
    while ($row = $dou->fetch_array($query)) {

        $order_list[] = $row;
    }
    $smarty->assign('order_list', $order_list);
    $smarty->display('order.htm');
} 

else if($rec == 'edit'){
    $smarty->assign('ur_here', $_LANG['order_edit']);
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';

    $query = $dou->select($dou->table('order'), '*', '`id` = \'' . $id . '\'');
    $order_detail = $dou->fetch_assoc($query);
    if($order_detail['order_trade_status']==1){
        $order_detail['order_trade_status_text'] = '支付成功';
    }
    else $order_detail['order_trade_status_text'] = '未支付';

    $province = $dou->get_one("SELECT area_name FROM " . $dou->table('areas') . " WHERE area_id = '".$order_detail['buyer_province']."'");
    $city = $dou->get_one("SELECT area_name FROM " . $dou->table('areas') . " WHERE area_id = '".$order_detail['buyer_city']."'");
    $area = $dou->get_one("SELECT area_name FROM " . $dou->table('areas') . " WHERE area_id = '".$order_detail['buyer_area']."'");
    $order_detail['area'] = $province.' '.$city.' '.$area;

    $smarty->assign('order', $order_detail);
    $smarty->display('order.htm');
}
elseif ($rec == 'del') {
    $order_id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'order.php');
    if (isset($_POST['confirm']) ? $_POST['confirm'] : '') {
        $dou->data(array('is_del'=>1));
        $dou->setTable('order');
        if($dou->update('id = '.$order_id,'order.php')){
            $dou->create_admin_log($_LANG['order_del'] . ': ' . $order_id);
            $dou->dou_msg($GLOBALS['_LANG']['del_succes'], 'order.php');
        }

    }
    else{
        $sql = "SELECT order_no FROM " . $dou->table('order')  ." WHERE id = ".$order_id;
        $order_no = $dou->get_one($sql);
        $_LANG['del_check'] = preg_replace('/d%/Ums', '订单'.$order_no, $_LANG['del_check']);
        $dou->dou_msg($_LANG['del_check'], 'order.php', '', '30', "order.php?rec=del&id=$order_id");

    }



}
?>