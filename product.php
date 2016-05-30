<?php
/**
 * DouPHP
 * --------------------------------------------------------------------------------------------------
 * 版权所有 2013-2015 漳州豆壳网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.douco.com
 * --------------------------------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在遵守授权协议前提下对程序代码进行修改和使用；不允许对程序代码以任何形式任何目的的再发布。
 * 授权协议：http://www.douco.com/license.html
 * --------------------------------------------------------------------------------------------------
 * Author: DouCo
 * Release Date: 2015-10-16
 */
define('IN_DOUCO', true);
require (dirname(__FILE__) . '/include/init.php');
require (dirname(__FILE__) . '/include/tool.class.php');
// 验证并获取合法的ID，如果不合法将其设定为-1
$id = $firewall->get_legal_id('product', $_REQUEST['id'], $_REQUEST['unique_id']);
$cat_id = $dou->get_one("SELECT cat_id FROM " . $dou->table('product') . " WHERE id = '$id'");
$parent_id = $dou->get_one("SELECT parent_id FROM " . $dou->table('product_category') . " WHERE cat_id = '" . $cat_id . "'");
if ($id == -1)
     header('location:'.ROOT_URL);
    
    /* 获取产品信息 */
$query = $dou->select($dou->table('product'), '*', '`id` = \'' . $id . '\'');
$product = $dou->fetch_array($query);

    /* 获取相关产品信息 */
$query = $dou->select($dou->table('product'), '*', '`type` = '.$product['type'].' and `cat_id` = \'' . $product['cat_id'] . '\' limit 5 ');
while ($row = $dou->fetch_assoc($query)) {
	$row['url'] = $dou->rewrite_url('product', $row['id']);
	$relate[] = $row;
}

// 格式化数据
$product['price'] = $product['price'] > 0 ? $dou->price_format($product['price']) : $_LANG['price_discuss'];
$product['add_time'] = date("Y-m-d", $product['add_time']);
//$product['date'] = date("Y-m-d", $product['date']);//新增

// 生成缩略图的文件名
$image = explode(".", $product['image']);
$product['thumb'] = ROOT_URL . $image[0] . "_thumb." . $image[1];
$product['image'] = ROOT_URL . $product['image'];

// 格式化自定义参数
foreach (explode(',', $product['defined']) as $row) {
    $row = explode('：', str_replace(":", "：", $row));
    
    if ($row['1']) {
        $defined[] = array (
                "arr" => $row['0'],
                "value" => $row['1'] 
        );
    }
}

// 赋值给模板-meta和title信息
$smarty->assign('page_title', $dou->page_title('product_category', $cat_id, $product['name']));
$smarty->assign('keywords', $product['keywords']);
$smarty->assign('description', $product['description']);

// 赋值给模板-导航栏
$smarty->assign('nav_top_list', $dou->get_nav('top'));
$smarty->assign('nav_middle_list', $dou->get_nav('middle', '0', 'product_category', $cat_id, $parent_id));
$smarty->assign('nav_bottom_list', $dou->get_nav('bottom'));
$smarty->assign('controller','product');
$cate = $dou->get_category('product_category', 0, $cat_id);
//print_r($cate);exit;
foreach($cate as $key => $val){
	if($val['cat_id'] == $product['cat_id']){
		$product['bigCate'] = $val['cat_name'];
		$product['smallCate'] = '';
		break;
	}
	foreach($val['child'] as $vk => $vv){
		if($vv['cat_id'] == $product['cat_id']){
			$product['bigCate'] = $val['cat_name'];
			$product['smallCate'] = $vv['cat_name'];
			break;
		}
	}
}

// 赋值给模板-数据
$smarty->assign('ur_here', $dou->ur_here('product_category', $cat_id, $product['name']));
// $smarty->assign('product_category', $dou->get_category('product_category', 0, $cat_id));
$smarty->assign('product', $product);
$smarty->assign('relate', $relate);
$smarty->assign('defined', $defined);

$smarty->assign('order_url',Tool::getHttpHost().'/order.php');

$smarty->display('product.dwt');
?>