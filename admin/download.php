<?php
define('IN_DOUCO', true);

require (dirname(__FILE__) . '/include/init.php');


if(empty($_GET['id'])){
	echo '<script>alert("操作无效，请重新选择");</script>';
	echo '<script>window.location.href="/admin/jianli.php";</script>';
}
$id = $_GET['id'] + 0;

$sql = 'SELECT name, position FROM '.$dou->table('jianli').' WHERE id = '.$id;
$res = $dou->query($sql);
if($res){
	while ($row = $dou->fetch_assoc($res)) {
		$file = $row;
	}
	// print_r($file);exit;
	$data = file_get_contents($file['position']);
	// header("Content-type:application/msword;"); //输出类型 
	// header("Content-Disposition:filename={$file['name']}");
	// // header("Accept-Ranges: bytes"); //文件单位
	// header("Content-Disposition: attachment; filename={$file['name']}");//下载时显示的名字
	header("Content-type: application/vnd.ms-excel; charset=gb2312");
	header("Content-Disposition: attachment; filename={$file['name']}");
	echo $data;
}