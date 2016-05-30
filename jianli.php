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
//$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';
//简历上传
require (dirname(__FILE__) . '/include/upload.class.php');
if(!empty($_POST['zpid'])){
	$zpid = $_POST['zpid'];
}

if(!empty($_POST['job'])){
	$job = $_POST['job'];
}

if(!empty($_POST['cat_name'])){
	$cat_name = $_POST['cat_name'];
}

if(!empty($_POST['cat_id'])){
	$cat_id = $_POST['cat_id'];
}

if(!empty($_FILES) && $_FILES['fil']['error'] == 0) {
	$Upload = new Upload();
	$des = $Upload->createDir().'/'.$Upload->randStr().$Upload->getExt($_FILES['fil']['name']);
	
	/*if(move_uploaded_file($_FILES['fil']['tmp_name'], ROOT.$des)) {
		$表名 ['fil'] = $des;
	}*/
	$jianli = $Upload->jian($_FILES['fil']);
	if($jianli == '0') {
		echo "<script>alert('文件不存在');</script>";
	    echo "<script>window.location.href='edi.php';</script>";
	} else if($jianli == '2') {
		echo "<script>alert('文件类型不正确,请选择doc或者docx格式的文件上传');</script>";
	    echo "<script>window.location.href='edi.php';</script>";
	} else if($jianli == '3') {
		echo "<script>alert('文件过大,不能上传');</script>";
	    echo "<script>window.location.href='edi.php';</script>";
	} else {
		$path = dirname(__FILE__).'/data/upload';
		$path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
		if(!file_exists($path)){
			mkdir($path, 0777, true);
		}
		$path .= $des;

		$flag = move_uploaded_file($_FILES['fil']['tmp_name'], $path );
		
		if($flag){
			
			/*
			$jlInfo = array();
			$jlInfo['name'] = $_FILES['fil']['name'];
			$jlInfo['add_time'] = time();
			$jlInfo['zhaopin_id'] = $zpid;
			$jlInfo['position'] = $path;
			$jlInfo['job'] = $job;
			$jlInfo['cat_name'] = $cat_name;
			*/

			$sql = 'INSERT INTO ' .$dou->table('jianli'). "(name, add_time, zhaopin_id, position , job , cat_id , cat_name ) values('".$_FILES["fil"]["name"]."', '". time() ."', '$zpid', '$path' , '$job' , '$cat_id' , '$cat_name' )";
			
			$dou->query($sql);

		    echo "<script>alert('上传成功');</script>";
		    echo "<script>window.location.href='zhaopin.php';</script>";
		}else{
		    echo "<script>alert('上传失败');</script>";
		    echo "<script>window.location.href='edi.php';</script>";
		}


	}
	

}


?>