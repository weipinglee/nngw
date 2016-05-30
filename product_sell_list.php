<?php
/**
 */
define('IN_DOUCO', true);

require (dirname(__FILE__) . '/include/init.php');

function product_sell_url(&$product_category){
    foreach($product_category as $k=>$v){

        $product_category[$k]['url'] = str_replace('product_category','product_sell_list',$product_category[$k]['url']);
        if(isset($product_category[$k]['child'])){
            product_sell_url($product_category[$k]['child']);
        }
}
}
// 验证并获取合法的ID，如果不合法将其设定为-1
$cat_id = $firewall->get_legal_id('product_category', $_REQUEST['id'], $_REQUEST['unique_id']);
if ($cat_id == -1) {
     header('location:'.ROOT_URL);
} else {
    $where = ' WHERE type = 1 and cat_id IN (' . $cat_id . $dou->dou_child_id('product_category', $cat_id) . ')';
}
    
// 获取分页信息
$page = $check->is_number($_REQUEST['page']) ? trim($_REQUEST['page']) : 1;
$limit = $dou->pager('product', ($_DISPLAY['product'] ? $_DISPLAY['product'] : 10), $page, $dou->rewrite_url('product_category', $cat_id), $where);

/* 获取产品列表 */
$sql = "SELECT id, cat_id, name, price, content, image1, image2,image3 , add_time, description FROM " . $dou->table('product') . $where . " ORDER BY id DESC" . $limit;
$query = $dou->query($sql);

while ($row = $dou->fetch_array($query)) {
    $url = $dou->rewrite_url('product', $row['id']); // 获取经过伪静态产品链接
    $add_time = date("Y-m-d", $row['add_time']);
    
    // 如果描述不存在则自动从详细介绍中截取
    $description = $row['description'] ? $row['description'] : $dou->dou_substr($row['content'], 150);
    
    // 生成缩略图的文件名
    $image = explode(".", $row['image']);
    // $image1 = explode(".", $row['image1']);
    // $image2 = explode(".", $row['image2']);
    // $image3 = explode(".", $row['image3']);
    $thumb = ROOT_URL . $image[0] . "_thumb." . $image[1];
    
    // 格式化价格
    $price = $row['price'] > 0 ? $dou->price_format($row['price']) : $_LANG['price_discuss'];
    
    $product_list[] = array (
            "id" => $row['id'],
            "cat_id" => $row['cat_id'],
            "name" => $row['name'],
            "price" => $price,
            "thumb" => $thumb,
            "add_time" => $add_time,
            "image1" => $row['image1'],
            "image2" => $row['image2'],
            "image3" => $row['image3'],
            "description" => $description,
            "url" => $url 
    );
}

// 获取分类信息
$sql = "SELECT * FROM " . $dou->table('product_category') . " WHERE cat_id = '$cat_id'";
$query = $dou->query($sql);
$cate_info = $dou->fetch_assoc($query);

// 赋值给模板-meta和title信息
$smarty->assign('page_title', $dou->page_title('product_category', $cat_id));
$smarty->assign('keywords', $cate_info['keywords']);
$smarty->assign('description', $cate_info['description']);

// 赋值给模板-导航栏
$smarty->assign('nav_top_list', $dou->get_nav('top'));
$smarty->assign('nav_middle_list', $dou->get_nav('middle', '0', 'product_category', $cat_id, $cate_info['parent_id']));
$smarty->assign('nav_bottom_list', $dou->get_nav('bottom'));
//print_r($cate_info);
// 赋值给模板-数据
$smarty->assign('ur_here', $dou->ur_here('product_category', $cat_id));
$smarty->assign('cate_info', $cate_info);
$product_category = $dou->get_category('product_category', 0, $cat_id);
product_sell_url($product_category);
$smarty->assign('product_category',$product_category );
$smarty->assign('product_list', $product_list);
$smarty->assign('product', $product);
$smarty->assign('controller','product');

//分类id
$smarty->assign('req_id', $_REQUEST['id']);

$smarty->display('product_sell_list.dwt');
?>