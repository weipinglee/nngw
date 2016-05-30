<?php
header('Content-type:text/html;charset=utf-8');
define('IN_DOUCO', true);
require (dirname(__FILE__) . '/include/init.php');
if(empty($_REQUEST['type'])){
	echo  0;
	exit;
}else{
	$type = $_REQUEST['type'];
}

function ajax_product_list(){
	global $dou, $check, $smarty;
	$max = $_REQUEST['max'];
	$min = $_REQUEST['min'];
	$req_id = $_REQUEST['req_id'];
	if (empty($req_id)) {
	    $where = ' WHERE price between '.$min.' and '.$max. ' ';
	} else {
	    $where = ' WHERE cat_id IN (' . $req_id . $dou->dou_child_id('product_category', $req_id) . ') and price between '.$min.' and '.$max. ' ';
	}
    
	// 获取分页信息
	$page = $check->is_number($_REQUEST['page']) ? trim($_REQUEST['page']) : 1;
	$limit = $dou->pager('product', ($_DISPLAY['product'] ? $_DISPLAY['product'] : 10), $page, $dou->rewrite_url('product_category', $cat_id), $where);

	/* 获取产品列表：按照选出来价格范围商品的价格从低到高*/
	//$sql = "SELECT id, cat_id, name, price, content, image, add_time, description FROM " . $dou->table('product') . $where . " ORDER BY id DESC" . $limit;
	$sql = "SELECT id, cat_id, name, price, content, image1, add_time, description FROM " . $dou->table('product') . $where . " ORDER BY price" . $limit;
	$query = $dou->query($sql);

	while ($row = $dou->fetch_array($query)) {
	    $url = $dou->rewrite_url('product', $row['id']); // 获取经过伪静态产品链接
	    $add_time = date("Y-m-d", $row['add_time']);
	    
	    // 如果描述不存在则自动从详细介绍中截取
	    $description = $row['description'] ? $row['description'] : $dou->dou_substr($row['content'], 150);
	    
	    // 生成缩略图的文件名
	    // $image = explode(".", $row['image1']);
	    // $thumb = ROOT_URL . $image[0] . "_thumb." . $image[1];
	    $thumb = ROOT_URL . $row['image1'];
	    
	    // 格式化价格
	    $price = $row['price'] > 0 ? $dou->price_format($row['price']) : $GLOBALS['_LANG']['price_discuss'];
	    
	    $product_list[] = array (
	            "id" => $row['id'],
	            "cat_id" => $row['cat_id'],
	            "name" => $row['name'],
	            "price" => $price,
	            "thumb" => $thumb,
	            "add_time" => $add_time,
	            "description" => $description,
	            "url" => $url ,
	            "thumb_width" => $smarty->_tpl_vars['site']['thumb_width'],
	            "thumb_height" => $smarty->_tpl_vars['site']['thumb_height'],
	    );
	}
	if(empty($product_list)){
		echo 0;
		exit;
	}
	echo json_encode($product_list);
	exit;
}

$type();