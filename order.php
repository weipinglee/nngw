<?php
/**
 *
 */
define('IN_DOUCO', true);
require (dirname(__FILE__) . '/include/init.php');

if(isset($_POST['submit'])){

}
else{

    $product_id = isset($_GET['id']) && intval($_GET['id']) ? $_GET['id'] : 0;
    if($product_id!=0){
        $sql = $dou->select($dou->table('product'), '*', '`id` = \'' . $product_id . '\'');
        $product = $dou->fetch_assoc($sql);

        $smarty->assign('product',$product);
        $smarty->display('order.dwt');
    }
    else{

    }

}


?>