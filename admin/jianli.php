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

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';



// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'jianli');

/**
 * +----------------------------------------------------------
 * 简历列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['jianli']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['jianli_add'],
            'href' => 'jianli.php?rec=add' 
    ));
    
    // 获取参数
    $cat_id = $check->is_number($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : 0;
    $keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    
    // 筛选条件
    $where = ' WHERE cat_id IN (' . $cat_id . $dou->dou_child_id('zhaopin_category', $cat_id) . ')';
    if ($keyword) {
        $where = $where . " AND job LIKE '%$keyword%'";
        $get = '&keyword=' . $keyword;
    }
    
    // 分页
    $page = $check->is_number($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $page_url = 'jianli.php' . ($cat_id ? '?cat_id=' . $cat_id : '');
    $limit = $dou->pager('jianli', 15, $page, $page_url, $where, $get);
    
    $sql = "SELECT id, name , add_time , zhaopin_id, position , job , cat_id , cat_name FROM " . $dou->table('jianli') . $where. " ORDER BY id DESC" . $limit;
    //echo $sql;exit;
    $query = $dou->query($sql);
    while ($row = $dou->fetch_array($query)) {
        // $cat_name = $dou->get_one("SELECT cat_name FROM " . $dou->table('jianli_category') . " WHERE cat_id = '$row[cat_id]'");
        $add_time = date("Y-m-d", $row['add_time']);
        
        $url =  $dou->rewrite_url('admin/download', $row['id']);
        
        $jianli_list[] = array (
                "id" => $row['id'],
                "name" => $row['name'],
                "position" => $row['position'],
                "zhaopin_id" => $row['zhaopin_id'],
                "add_time" => $add_time,
                'url'=>$url,
                "job" => $row['job'],
                "cat_name" => $row['cat_name']
        );
    }
    
    //var_dump($jianli_list);exit;
    // 首页显示招聘数量限制框
    for($i = 1; $i <= $_CFG['home_display_jianli']; $i++) {
        $sort_bg .= "<li><em></em></li>";
    }
    
    // 赋值给模板
    $smarty->assign('if_sort', $_SESSION['if_sort']);
    $smarty->assign('sort', get_sort_jianli());
    $smarty->assign('sort_bg', $sort_bg);
    $smarty->assign('cat_id', $cat_id);
    $smarty->assign('keyword', $keyword);
    $smarty->assign('zhaopin_category', $dou->get_category_nolevel('zhaopin_category'));//工作地点
    $smarty->assign('jianli_list', $jianli_list);
    // var_dump($jianli_list);exit;
    $smarty->display('jianli.htm');
} 


/**
 * +----------------------------------------------------------
 * 批量操作选择
 * +----------------------------------------------------------
 */
elseif ($rec == 'action') {
    if (is_array($_POST['checkbox'])) {
        if ($_POST['action'] == 'del_all') {
            // 批量招聘删除
            $dou->del_all('jianli', $_POST['checkbox'], 'id', 'image');
        } else {
            $dou->dou_msg($_LANG['select_empty']);
        }
    } else {
        $dou->dou_msg($_LANG['jianli_select_empty']);
    }
}

/**
 * +----------------------------------------------------------
 * 获取首页显示招聘
 * +----------------------------------------------------------
 */
function get_sort_jianli() {
    $limit = $GLOBALS['_DISPLAY']['home_jianli'] ? ' LIMIT ' . $GLOBALS['_DISPLAY']['home_jianli'] : '';
    $sql = "SELECT id, job FROM " . $GLOBALS['dou']->table('jianli') . " WHERE sort > 0 ORDER BY sort DESC" . $limit;
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_array($query)) {
        $sort[] = array (
                "id" => $row['id'],
                "job" => $row['job'] 
        );
    }
    
    return $sort;
}
?>