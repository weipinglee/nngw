<?php
define('IN_DOUCO',true);
require_once(dirname(__FILE__).'\include\init.php');
$rec=$check->is_rec($_REQUEST['rec'])?$_REQUEST['rec']:'default';

//图片上传
include_once (ROOT_PATH.'/include/upload.class.php');
$images_dir='images/frdLink/';
$thumb_dir='';
$img=new Upload(ROOT_PATH.$images_dir,$thumb_dir);
if(!file_exists(ROOT_PATH.$images_dir)){
    mkdir(ROOT_PATH.$images_dir,0777);
}
//赋值给模板
$smarty->assign('rec',$rec);
$smarty->assign('cur','frdLink');
/*
 * 帮助列表
 * */
if($rec=='default'){
    $smarty->assign('ur_here',$_LANG['frdLink']);
    $smarty->assign('action_link',['text'=>$_LANG['linkAdd'],'next'=>'frdLink.php?rec=add']);
    $page=$check->is_number($_REQUEST['page'])?$_REQUEST['page']:1;

   // $where='name like %'.$check->is_text($_REQUEST['keyword'])?$_REQUEST['keyword']:''.'%';
    $keyword=isset($_REQUEST['keyword'])?trim($_REQUEST['keyword']):'';
    if($keyword){
    $where=' where keyword like %'.$keyword.'%';
    }
    $get='&keyword='.$keyword;
    $pageUrl='frdLink.php';
    $limit=$dou->pager('frd_link',15,$page,$pageUrl,$where,$get);
    $sql='select * from'.$dou->table('frdlink').$where.' order by id ASC'.$limit;
    $query=$dou->query($sql);
    while($arr=$dou->fetch_assoc($query)){
        $frdLinkList[]=[
          'id'=>$arr['id'],
          'name'=>$arr['name'],
          'url'=>$arr['url'],
          'img'=>$arr['img'],
          'status'=>$arr['status']
        ];

    }
    $smarty->assign('frdLinkList',$frdLinkList);
    $smarty->display('frdLink.htm');
}
elseif($rec=='add'){

    $smarty->assign('ur_here',$_LANG['linkAdd']);
    $smarty->assign('action_link',['text'=>$_LANG['frdLink'],'next'=>'frdLink.php']);
    //CSRF防御令牌生成
    $smarty->assign('token',$firewall->set_token('linkAdd'));

    $smarty->assign('form_action','insert');
    $smarty->display('frdLink.htm');
}
elseif ($rec=='insert') {
    if (empty($_POST['name']))
        $dou->dou_msg($_LANG['linkName'] . $_LANG['is_empty']);
    //新增
    if(empty($_POST['url'])){
        $dou->dou_msg($_LANG['frdlink_url'].$_LANG['is_empty']);
    }
    if(!$check->is_url($_POST['url'])){
        $dou->dou_msg('链接地址不正确');
    }
    // 判断是否有上传图片/上传图片生成
  // var_dump($_FILES);
   if ($_FILES['img']['name'] != "") {
        // 生成图片文件名
        $file_name = date('Ymd');
        for($i = 0; $i < 6; $i++) {
            $file_name .= chr(mt_rand(97, 122));
        }

        // 其中image指的是上传的文本域名称，$file_name指的是生成的图片文件名
        $upfile = $img->upload_image('img', $file_name);
        $file = $images_dir . $upfile;
        /*$ssss=$img->make_thumb($upfile, 100, 100); // 生成缩略图
        var_dump($ssss);
        var_dump($file);
        die;*/
    }
    // 格式化自定义参数
    $_POST['defined'] = str_replace("\r\n", ',', $_POST['defined']);
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token'], 'linkAdd');
    $sql = "INSERT INTO " . $dou->table('frdlink') . " (id,name,url ,img ,status)" . " VALUES (NULL, '$_POST[name]', '$_POST[url]','$file', '$_POST[status]')";
    $dou->query($sql);
    $dou->create_admin_log($_LANG['linkAdd'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['frdlink_add_success'], 'frdLink.php');
} elseif($rec=='edit'){
    $smarty->assign('ur_here',$_LANG['frdlink_edit']);
    $smarty->assign('action_link',[
        'text'=>$_LANG['frdLink'],
        'next'=>'frdLink.php'
    ]);
    $id=$check->is_number($_GET['id'])?$_GET['id']:'';
    if($id==''){
        $dou->dou_msg('数据不合法');
    }
    $query=$dou->select($dou->table('frdlink'),'*','id='.$id);
    $frdLinkInfo=$dou->fetch_assoc($query);
    $smarty->assign('frdlink',$frdLinkInfo);
    $smarty->assign('form_action','update');
    $token=$firewall->set_token('linkEdit');
    $smarty->assign('token',$token);
    $smarty->display('frdLink.htm');
}elseif($rec=='update'){
    if(empty($_POST['name'])){
        $dou->dou_msg($_LANG['linkName'].$_LANG['is_empty']);
    }
    if(empty($_POST['url'])){
        $dou->dou_msg($_LANG['linkNameUrl'].$_LANG['is_empty']);
    }
    if(!$check->is_url($url)){
        $dou->dou_msg('链接地址不正确');
    }
    $id=$check->is_number($_REQUEST['id'])?$_REQUEST['id']:'';
    if($id==''){
        $dou->dou_msg('数据不合法');
    }
    if($_FILES['img']['name']!=''){
        // 生成图片文件名
        $file_name = date('Ymd');
        for($i = 0; $i < 6; $i++) {
            $file_name .= chr(mt_rand(97, 122));
        }

        // 其中image指的是上传的文本域名称，$file_name指的是生成的图片文件名
        $upfile = $img->upload_image('img', $file_name);
        $file = $images_dir . $upfile;
    }else{
        $file=$_POST['image'];
    }
    $firewall->check_token($_REQUEST['token'],'linkEdit');
    $sql='update '.$dou->table('frdlink').' set `id`=NULL,`name`=\''.$_POST['name'].'\',`url`=\''.$_POST['url'].'\',`img`=\''.$file.'\',`status`='.$_POST['status'].' where id='.$id;
    $dou->query($sql);
    $dou->create_admin_log($_LANG['frdlink_edit'] . ': ' . $_POST['name']);
    $dou->dou_msg($_LANG['frdlink_edit_success'], 'frdLink.php');
}elseif($rec=='del_all'){
    if(is_array($_POST['checkbox'])){

        //批量删除
        $dou->del_all('frdlink',$_POST['checkbox'],'id','img');
    }else{
        $dou->dou_msg($_LANG['select_empty']);
    }
}elseif($rec=='del'){
    $id=$check->is_number($_REQUEST['id'])?$_REQUEST['id']:$dou->dou_msg($_LANG['illegal'],'frdLink.php');
    $frdLink=$dou->get_one('select name from '.$dou->table('frdlink').' where id='.$id);
    $img=$dou->get_one('select img from'.$dou->table('frdlink').' where id='.$id);
    $dou->del_image($img);
    $dou->create_admin_log($_LANG['frdlink_del'].':'.$frdLink);
    $dou->delete($dou->table('frdlink'),'id='.$id,'frdLink.php');
}


?>