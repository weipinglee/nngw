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

if (isset($_REQUEST['mobile'])) setcookie('client', 'pc'); // 判断时候强制在移动端中显示PC版

require (dirname(__FILE__) . '/include/init.php');
//var_dump($GLOBALS['_LANG']);exit;

// 如果存在搜索词则转入搜索页面
if ($_REQUEST['s']) {
    if ($check->is_text($keyword = trim($_REQUEST['s']))) {
        require (ROOT_PATH . 'include/search.inc.php');
    } else {
        $dou->dou_msg($_LANG['search_keyword_wrong']);
    }
}

// 获取关于我们信息
$sql = "SELECT * FROM " . $dou->table('page') . " WHERE id = '1'";
$query = $dou->query($sql);
$about = $dou->fetch_array($query);

// 写入到index数组
$index['about_name'] = $about['page_name'];
$index['about_content'] = $dou->dou_substr($about['content'], 300);
$index['about_link'] = $dou->rewrite_url('page', '1');
$index['cur'] = true;

// 赋值给模板-meta和title信息
$smarty->assign('page_title', $dou->page_title());
$smarty->assign('keywords', $_CFG['site_keywords']);
$smarty->assign('description', $_CFG['site_description']);

// 赋值给模板-导航栏
$smarty->assign('nav_top_list', $dou->get_nav('top'));
$smarty->assign('nav_middle_list', $dou->get_nav('middle'));
//var_dump($dou->get_nav('middle'));exit;
$smarty->assign('nav_bottom_list', $dou->get_nav('bottom'));

// 赋值给模板-数据
$smarty->assign('show_list', $dou->get_show_list());
$smarty->assign('link', get_link_list());

$smarty->assign('recommend_product', $dou->get_list('product', 'ALL', $_DISPLAY['home_product'], 'sort DESC'));
// $smarty->assign('recommend_article', $dou->get_list('article', 'ALL', $_DISPLAY['home_article'], 'sort DESC'));
$sql = 'select cat_id, cat_name, unique_id from dou_article_category';
$query = $dou->query($sql);
while( $tmp = $dou->fetch_assoc($query)){
    $tmp['url'] = $dou->rewrite_url('article_category', $tmp['cat_id']);
    $cate[] = $tmp;
}
foreach($cate as $key => $val){
    $cate[$val['unique_id']]['name'] = $val['cat_name'];
    $cate[$val['unique_id']]['url'] = $val['url'];
    $cate[$val['unique_id']]['article'] = $dou->get_list('article', $val['cat_id'], $_DISPLAY['home_article'], 'sort DESC'); 
    unset($cate[$key]);
}
// print_r($dou->get_list('article', 'ALL', $_DISPLAY['home_article'], 'sort DESC'));exit;


$smarty->assign('recommend_article', $cate);

$smarty->display('wuhu.dwt');

/**
 * +----------------------------------------------------------
 * 获取下级幻灯列表
 * +----------------------------------------------------------
 */
function get_link_list() {
    $sql = "SELECT * FROM " . $GLOBALS['dou']->table('link') . " ORDER BY sort ASC, id ASC";
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_array($query)) {
        $link_list[] = array (
                "id" => $row['id'],
                "link_name" => $row['link_name'],
                "link_url" => $row['link_url'],
                "sort" => $row['sort'] 
        );
    }
    
    return $link_list;
}
?>