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

// 图片上传
include_once (ROOT_PATH . 'include/upload.class.php');
$images_dir = 'images/product/'; // 文件上传路径，结尾加斜杠
$thumb_dir = ''; // 缩略图路径（相对于$images_dir） 结尾加斜杠，留空则跟$images_dir相同
$img = new Upload(ROOT_PATH . $images_dir, $thumb_dir); // 实例化类文件
if (!file_exists(ROOT_PATH . $images_dir)) {
    mkdir(ROOT_PATH . $images_dir, 0777);
}

$type = isset($_GET['type']) && $_GET['type'] == 1 ? 1 : 0;

// 赋值给模板
$smarty->assign('rec', $rec);
if($type==1){
    $smarty->assign('cur', 'product_sell');

}
else {
    $smarty->assign('cur', 'product');
}
$smarty->assign('type', $type);
/**
 * +----------------------------------------------------------
 * 产品列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['product']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['product_add'],
            'href' => 'product.php?rec=add' 
    ));
    
    // 获取参数
    $cat_id = $check->is_number($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : 0;
    $keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
    
    // 筛选条件

    $where = ' WHERE cat_id IN (' . $cat_id . $dou->dou_child_id('product_category', $cat_id) . ')';
    if ($keyword) {
        $where = $where . " AND name LIKE '%$keyword%'";
        $get = '&keyword=' . $keyword;
    }
    $where .= " AND type=".$type;
    // 分页
    $page = $check->is_number($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $page_url = 'product.php' . ($cat_id ? '?cat_id=' . $cat_id : '');
    $limit = $dou->pager('product', 15, $page, $page_url, $where, $get);
    
    $sql = "SELECT id, name, cat_id, add_time FROM " . $dou->table('product') . $where . " ORDER BY id DESC" . $limit;
    $query = $dou->query($sql);
    while ($row = $dou->fetch_array($query)) {
        $cat_name = $dou->get_one("SELECT cat_name FROM " . $dou->table('product_category') . " WHERE cat_id = '$row[cat_id]'");
        $add_time = date("Y-m-d", $row['add_time']);
        
        $product_list[] = array (
                "id" => $row['id'],
                "cat_id" => $row['cat_id'],
                "cat_name" => $cat_name,
                "name" => $row['name'],
                "add_time" => $add_time 
        );
    }
    
    // 首页显示商品数量限制框
    for($i = 1; $i <= $_CFG['home_display_product']; $i++) {
        $sort_bg .= "<li><em></em></li>";
    }
    
    // 赋值给模板
    $smarty->assign('if_sort', $_SESSION['if_sort']);
    $smarty->assign('sort', get_sort_product());
    $smarty->assign('sort_bg', $sort_bg);
    $smarty->assign('cat_id', $cat_id);
    $smarty->assign('keyword', $keyword);
    $smarty->assign('product_category', $dou->get_category_nolevel('product_category'));
    $smarty->assign('product_list', $product_list);
    
    $smarty->display('product.htm');
} 

/**
 * +----------------------------------------------------------
 * 产品添加
 * +----------------------------------------------------------
 */
elseif ($rec == 'add') {
    $smarty->assign('ur_here', $_LANG['product_add']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['product'],
            'href' => 'product.php' 
    ));
    
    // 格式化自定义参数，并存到数组$product，商品编辑页面中调用商品详情也是用数组$product，
    if ($_DEFINED['product']) {
        $defined = explode(',', $_DEFINED['product']);
        foreach ($defined as $row) {
            $defined_product .= $row . "：\n";
        }
        $product['defined'] = trim($defined_product);
        $product['defined_count'] = count(explode("\n", $product['defined'])) * 2;
    }
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->set_token('product_add'));
    
    // 赋值给模板
    $smarty->assign('form_action', 'insert');
    $smarty->assign('product_category', $dou->get_category_nolevel('product_category'));
    $smarty->assign('product', $product);
    
    $smarty->display('product.htm');
} 

elseif ($rec == 'insert') {
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['name'] . $_LANG['is_empty']);
    if (!$check->is_price($_POST['price'] = trim($_POST['price'])))
        $dou->dou_msg($_LANG['price_wrong']);

    // 判断是否有上传图片/上传图片生成
    /*
    if ($_FILES['image1']['name'] != '') {
        $upfile1 = $img->upload_image('image1', $id); // 上传的文件域
        $file1 = $images_dir . $upfile1;
        
        //$img->make_thumb($upfile1, $_CFG['thumb1_width'], $_CFG['thumb1_height']);
    }else if($_FILES['image2']['name'] != '') {
        $upfile2 = $img->upload_image('image2', $id); // 上传的文件域
        $file2 = $images_dir . $upfile2;
        
        //$img->make_thumb($upfile2, $_CFG['thumb2_width'], $_CFG['thumb2_height']);
    }else if($_FILES['image3']['name'] != '') {
        $upfile3 = $img->upload_image('image3', $id); // 上传的文件域
        $file3 = $images_dir . $upfile3;

        //$img->make_thumb($upfile3, $_CFG['thumb3_width'], $_CFG['thumb3_height']);
    }
    */

    if(!empty($_FILES)){
        $file = array();
        foreach($_FILES as $key => $val){
           $file[] = $images_dir . $img->upload_image( $key , $id); // 上传的文件域
        }
    }

    $add_time = time();
    
    // 格式化自定义参数
    $_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
    $type = isset($_POST['type'])&&$_POST['type']==1 ? 1 : 0;
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token'], 'product_add');
    //新增字段
    $sql = "INSERT INTO " . $dou->table('product') . " (id, cat_id, name, price, defined, content, image1 , image2 , image3 , keywords, add_time, description , num ,cangku , pinzhong, date , length , width , height , type)" . " VALUES (NULL, '$_POST[cat_id]', '$_POST[name]', '$_POST[price]', '$_POST[defined]', '$_POST[content]', '$file[0]', '$file[1]' , '$file[2]' , '$_POST[keywords]', '$add_time', '$_POST[description]' , '$_POST[num]' ,'$_POST[cangku]' , '$_POST[pinzhong]' , '$_POST[date]' , '$_POST[length]' , '$_POST[width]' , '$_POST[height]' , '$type')";
    $dou->query($sql);
    
    // 为了产品图片管理方便，重新以产品ID定义图片名称
    /*if ($_FILES['image']['name'] != '') {
        // 格式化图片名称
        $good_id = mysql_insert_id();
        $no_ext = explode('.', $file);
        $file_thumb = $no_ext[0] . '_thumb' . '.' . $no_ext[1];
        $new_name = $images_dir . $good_id . '.' . $no_ext[1];
        $new_name_thumb = $images_dir . $good_id . '_thumb' . '.' . $no_ext[1];
        
        // 图片存在的话，先删除图片
        @ unlink('../' . $new_name);
        @ unlink('../' . $new_name_thumb);
        
        // 重命名图片名称
        rename('../' . $file, '../' . $new_name);
        rename('../' . $file_thumb, '../' . $new_name_thumb);
        $resql = "update " . $dou->table('product') . " SET image='$new_name' WHERE id='$good_id'";
    }
    $dou->query($resql);
    */
    $dou->create_admin_log($_LANG['product_add'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['product_add_succes'], 'product.php');
} 

/**
 * +----------------------------------------------------------
 * 产品编辑
 * +----------------------------------------------------------
 */
elseif ($rec == 'edit') {
    $smarty->assign('ur_here', $_LANG['product_edit']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['product'],
            'href' => 'product.php' 
    ));
    
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
    
    $query = $dou->select($dou->table('product'), '*', '`id` = \'' . $id . '\'');
    $product = $dou->fetch_array($query);
    
    // 格式化自定义参数
    if ($_DEFINED['product'] || $product['defined']) {
        $defined = explode(',', $_DEFINED['product']);
        foreach ($defined as $row) {
            $defined_product .= $row . "：\n";
        }
        // 如果商品中已经写入自定义参数则调用已有的
        $product['defined'] = $product['defined'] ? str_replace(",", "\n", $product['defined']) : trim($defined_product);
        $product['defined_count'] = count(explode("\n", $product['defined'])) * 2;
    }
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->set_token('product_edit'));
    
    // 赋值给模板
    $smarty->assign('form_action', 'update');
    $smarty->assign('product_category', $dou->get_category_nolevel('product_category'));
    $smarty->assign('product', $product);
    
    $smarty->display('product.htm');
} 

elseif ($rec == 'update') {
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['name'] . $_LANG['is_empty']);
    if (!$check->is_price($_POST['price'] = trim($_POST['price'])))
        $dou->dou_msg($_LANG['price_wrong']);
        
    // 上传图片生成
    if ($_FILES['image1']['name'] != "") {
        // 分析商品图片名称
        $basename1 = basename($_POST['image1']);
        $file_name1 = substr($basename1, 0, strrpos($basename1, '.'));
        
        $upfile1 = $img->upload_image('image1', $file_name1); // 上传的文件域
        $file1 = $images_dir . $upfile1;
        //$img->make_thumb($upfile, $_CFG['thumb_width'], $_CFG['thumb_height']);
        
        $up_file1 = ", image1='$file1'";
    }

    if ($_FILES['image2']['name'] != "") {
        // 分析商品图片名称
        $basename2 = basename($_POST['image2']);
        $file_name2 = substr($basename2, 0, strrpos($basename2, '.'));
        
        $upfile2 = $img->upload_image('image2', $file_name2); // 上传的文件域
        $file2 = $images_dir . $upfile2;
        //$img->make_thumb($upfile, $_CFG['thumb_width'], $_CFG['thumb_height']);
        
        $up_file2 = ", image2='$file2'";
    }

    if ($_FILES['image3']['name'] != "") {
        // 分析商品图片名称
        $basename3 = basename($_POST['image3']);
        $file_name3 = substr($basename3, 0, strrpos($basename3, '.'));
        
        $upfile3 = $img->upload_image('image3', $file_name3); // 上传的文件域
        $file3 = $images_dir . $upfile3;
        //$img->make_thumb($upfile, $_CFG['thumb_width'], $_CFG['thumb_height']);
        
        $up_file3 = ", image3='$file3'";
    }
    
    // 格式化自定义参数
    $_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
    
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token'], 'product_edit');
    
    $sql = "update " . $dou->table('product') . " SET cat_id = '$_POST[cat_id]', name = '$_POST[name]', price = '$_POST[price]', defined = '$_POST[defined]' ,content = '$_POST[content]'" . $up_file1 . $up_file2 . $up_file3 . ", keywords = '$_POST[keywords]', description = '$_POST[description]' , num = '$_POST[num]' , cangku = '$_POST[cangku]' , pinzhong = '$_POST[pinzhong]' ,date = '$_POST[date]' , length = '$_POST[length]' ,width = '$_POST[width]' , height = '$_POST[height]' WHERE id = '$_POST[id]'";
    // echo $sql;exit;
    $dou->query($sql);
    
    $dou->create_admin_log($_LANG['product_edit'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['product_edit_succes'], 'product.php');
} 

/**
 * +----------------------------------------------------------
 * 重新生成产品图片
 * +----------------------------------------------------------
 */
elseif ($rec == 're_thumb') {
    $smarty->assign('ur_here', $_LANG['product_thumb']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['product'],
            'href' => 'product.php' 
    ));
    
    $sql = "SELECT id, image1 ,image2 ,image3 FROM " . $dou->table('product') . "ORDER BY id ASC";
    $count = mysql_num_rows($query = $dou->query($sql));
    $mask['count'] = preg_replace('/d%/Ums', $count, $_LANG['product_thumb_count']);
    $mask_tag = '<i></i>';
    $mask['confirm'] = isset($_POST['confirm']) ? $_POST['confirm'] : 0;
    
    for($i = 1; $i <= $count; $i++)
        $mask['bg'] .= $mask_tag;
    
    $smarty->assign('mask', $mask);
    $smarty->display('product.htm');
    
    if (isset($_POST['confirm'])) {
        echo ' ';
        while ($row = $dou->fetch_array($query)) {
            $img->make_thumb(basename($row['image']), $_CFG['thumb_width'], $_CFG['thumb_height']);
            echo "<script type=\"text/javascript\">mask('" . $mask_tag . "');</script>";
            flush();
            ob_flush();
        }
        echo "<script type=\"text/javascript\">success();</script>\n</body>\n</html>";
    }
} 

/**
 * +----------------------------------------------------------
 * 首页商品筛选
 * +----------------------------------------------------------
 */
elseif ($rec == 'sort') {
    $_SESSION['if_sort'] = $_SESSION['if_sort'] ? false : true;
    
    // 跳转到上一页面
    $dou->dou_header($_SERVER['HTTP_REFERER']);
} 

/**
 * +----------------------------------------------------------
 * 设为首页显示商品
 * +----------------------------------------------------------
 */
elseif ($rec == 'set_sort') {
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'product.php');
    
    $max_sort = $dou->get_one("SELECT sort FROM " . $dou->table('product') . " ORDER BY sort DESC");
    $new_sort = $max_sort + 1;
    $dou->query("UPDATE " . $dou->table('product') . " SET sort = '$new_sort' WHERE id = '$id'");
    
    $dou->dou_header($_SERVER['HTTP_REFERER']);
} 

/**
 * +----------------------------------------------------------
 * 取消首页显示商品
 * +----------------------------------------------------------
 */
elseif ($rec == 'del_sort') {
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'product.php');
    
    $dou->query("UPDATE " . $dou->table('product') . " SET sort = '' WHERE id = '$id'");
    
    $dou->dou_header($_SERVER['HTTP_REFERER']);
} 

/**
 * +----------------------------------------------------------
 * 产品删除
 * +----------------------------------------------------------
 */
elseif ($rec == 'del') {
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : $dou->dou_msg($_LANG['illegal'], 'product.php');

    $name = $dou->get_one("SELECT name FROM " . $dou->table('product') . " WHERE id = '$id'");
    
    if (isset($_POST['confirm']) ? $_POST['confirm'] : '') {
        // 删除相应商品图片
        $image1 = $dou->get_one("SELECT image1 FROM " . $dou->table('product') . " WHERE id = '$id'");
        $image2 = $dou->get_one("SELECT image2 FROM " . $dou->table('product') . " WHERE id = '$id'");
        $image3 = $dou->get_one("SELECT image3 FROM " . $dou->table('product') . " WHERE id = '$id'");
        $dou->del_image($image1);
        $dou->del_image($image2);
        $dou->del_image($image3);
        
        $dou->create_admin_log($_LANG['product_del'] . ': ' . $name);
        $dou->delete($dou->table('product'), "id = $id", 'product.php');
    } else {
        $_LANG['del_check'] = preg_replace('/d%/Ums', $name, $_LANG['del_check']);
        $dou->dou_msg($_LANG['del_check'], 'product.php', '', '30', "product.php?rec=del&id=$id");
    }
} 

/**
 * +----------------------------------------------------------
 * 批量操作选择
 * +----------------------------------------------------------
 */
elseif ($rec == 'action') {
    if (is_array($_POST['checkbox'])) {
        if ($_POST['action'] == 'del_all') {
            // 批量商品删除
            $dou->del_all('product', $_POST['checkbox'], 'id', 'image');
        } elseif ($_POST['action'] == 'category_move') {
            // 批量移动分类
            $dou->category_move('product', $_POST['checkbox'], $_POST['new_cat_id']);
        } else {
            $dou->dou_msg($_LANG['select_empty']);
        }
    } else {
        $dou->dou_msg($_LANG['product_select_empty']);
    }
}

/**
 * +----------------------------------------------------------
 * 获取首页显示商品
 * +----------------------------------------------------------
 */
function get_sort_product() {
    $limit = $GLOBALS['_DISPLAY']['home_product'] ? ' LIMIT ' . $GLOBALS['_DISPLAY']['home_product'] : '';
    $sql = "SELECT id, name, image1 ,image2,image3 FROM " . $GLOBALS['dou']->table('product') . " WHERE sort > 0 ORDER BY sort DESC" . $limit;
    $query = $GLOBALS['dou']->query($sql);
    while ($row = $GLOBALS['dou']->fetch_array($query)) {
        $image1 = ROOT_URL . $row['image1'];
        $image2 = ROOT_URL . $row['image2'];
        $image3 = ROOT_URL . $row['image3'];
        
        $sort[] = array (
                "id" => $row['id'],
                "name" => $row['name'],
                "image1" => $image1,
                "image2" => $image2,
                "image3" => $image3
        );
    }
    
    return $sort;
}
?>