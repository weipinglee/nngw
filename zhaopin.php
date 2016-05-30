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
//新
define('IN_DOUCO', true);

require (dirname(__FILE__) . '/include/init.php');

$tb = $dou->table('zhaopin');

if(!empty($_REQUEST['id'])){
    $cat_id = $_REQUEST['id'] + 0;
    $where = " where $tb.cat_id = ".$cat_id. ' ';
}else{
    $cat_id = '';
    $where = '';
}

// 获取分页信息 新
$page = $check->is_number($_REQUEST['page']) ? trim($_REQUEST['page']) : '1';
$limit = $dou->pager('zhaopin', ($_DISPLAY['zhaopin'] ? $_DISPLAY['zhaopin'] : 10), $page, $dou->rewrite_url('zhaopin', $cat_id), $where);

/* 获取招聘信息列表 新 */

$sql = "SELECT $tb.id, $tb.cat_id, $tb.job, $tb.salary , $tb.add_time, $tb.zhize , $tb.zige, c.cat_name  FROM " . $dou->table('zhaopin') .' left join '. $dou->table('zhaopin_category') . " as c on $tb.cat_id = c.cat_id " . $where . " ORDER BY $tb.add_time DESC" . $limit;
$query = $dou->query($sql);
// echo $sql;exit;
while($row = $dou->fetch_assoc($query)){
    $row['add_time'] = date("Y-m-d", $row['add_time']);
    $row['url'] = $dou->rewrite_url('edi', $row['id']);
    $zhaopin[] = $row ;
}
// var_dump($limit);exit;
$sql = "SELECT cat_id,cat_name FROM " . $dou->table('zhaopin_category') .  " ORDER BY sort " ;
$query = $dou->query($sql);
while($row = $dou->fetch_assoc($query)){
    $row['url'] = $dou->rewrite_url('zhaopin', $row['cat_id']);
    $zh[] = $row ;
}


// 格式化自定义参数
foreach (explode(',', $zhaopin['defined']) as $row) {
    $row = explode('：', str_replace(":", "：", $row));
    if ($row['1']) {
        $defined[] = array (
                "arr" => $row['0'],
                "value" => $row['1'] 
        );
    }
}


// 赋值给模板-meta和title信息
$smarty->assign('page_title', $dou->page_title('zhaopin_category', $cat_id, $zhaopin['title']));
$smarty->assign('keywords', $zhaopin['keywords']);
$smarty->assign('description', $zhaopin['description']);

// 赋值给模板-导航栏 新
$smarty->assign('nav_top_list', $dou->get_nav('top'));
$smarty->assign('nav_middle_list', $dou->get_nav('middle', '0', 'zhaopin', $cat_id, $cate_info['parent_id']));
//var_dump($dou->get_nav('middle', '0', 'zhaopin_category', $cat_id, $cate_info['parent_id']));exit;
$smarty->assign('nav_bottom_list', $dou->get_nav('bottom'));
// 赋值给模板-数据
$smarty->assign('ur_here', $dou->ur_here('zhaopin_category', $cat_id));
$smarty->assign('cate_info', $cate_info);
$smarty->assign('zhaopin', $zhaopin);
$smarty->assign('zh', $zh);//zhaopin_category表的
$smarty->assign('zhaopin_category', $dou->get_category('zhaopin_category', 0, ''));
$smarty->assign('controller','zhaopin');
//招聘信息分页
$pageBar = getPageBar($smarty->_tpl_vars['pager']);

$smarty->assign('pageBar', $pageBar);

function getPageBar( $pager ){
    $href = substr($pager['last'], 0, strrpos($pager['last'], 'page=')) . 'page='; //页面链接
    
    $curr = $pager['page']; //当前页码

    $count = $pager['page_count']; //总页数

    $left = max($curr-2, 1); //初步计算最左边页码

    $right = min($left + 4, $count); //计算最右边页码
    $left = max($right-4, 1);// 计算最终左边的页码
    $pageBar = array();
    for($i=$left; $i<=$right; $i++){
        $pageBar[$i]['code'] = $i;
        $pageBar[$i]['link'] = $href.$i;
    }
     
    return $pageBar;
}


$smarty->display('zhaopin.dwt');
?>