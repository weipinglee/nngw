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

// 验证并获取合法的ID，如果不合法将其设定为-1
$id = $firewall->get_legal_id('page', $_REQUEST['id'], $_REQUEST['unique_id']);

//简历上传
include_once(ROOT_PATH . 'include/upload.class.php');
/*if ($id == -1)
    $dou->dou_msg($GLOBALS['_LANG']['page_wrong'], ROOT_URL);*/
    
    // 获取单页面信息
/*$page = get_page_info($id);
$top_id = $page['parent_id'] == 0 ? $id : $page['parent_id'];*/

// 赋值给模板-meta和title信息
/*$smarty->assign('page_title', $dou->page_title('page', '', $page['page_name']));
$smarty->assign('keywords', $page['keywords']);
$smarty->assign('description', $page['description']);*/

// 赋值给模板-导航栏
//$smarty->assign('nav_top_list', $dou->get_nav('top'));
$smarty->assign('nav_middle_list', $dou->get_nav('middle', '0', 'page', $id));
//$smarty->assign('nav_bottom_list', $dou->get_nav('bottom'));

// 赋值给模板-数据
/*$smarty->assign('ur_here', $dou->ur_here('page', '', $page['page_name']));
$smarty->assign('page_list', $dou->get_page_list($top_id, $id));
$smarty->assign('top', get_page_info($top_id));*/
//$smarty->assign('page', $page);
$smarty->assign('edi', $edi);
$jianli['url'] = $GLOBALS['dou']->rewrite_url('jianli', '');
$smarty->assign('jianli', $jianli);
$smarty->assign('zpid', $_GET['id'] + 0);
$smarty->assign('controller','zhaopin');

//两表联查，查出dou_zhaopin表的job和cat_name字段
$sc = $dou->table('zhaopin');
$sql = "SELECT $sc.job , d.cat_id , d.cat_name FROM " . $dou->table('zhaopin') . ' left join '. $dou->table('zhaopin_category') . " as d on $sc.cat_id = d.cat_id where id= " . $_GET['id'] ;
$query = $dou->query($sql);

while($row = $dou->fetch_assoc($query)){
    $jc[] = $row ;
}

$smarty->assign('job', $jc[0]['job']);
$smarty->assign('cat_name', $jc[0]['cat_name']);
$smarty->assign('cat_id', $jc[0]['cat_id']);
$smarty->display('edi.dwt');


?>