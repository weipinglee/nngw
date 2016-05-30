<?php
/**
 * 异步获取地区数据
 */
define('IN_DOUCO', true);
require (dirname(__FILE__) . '/include/init.php');
require (dirname(__FILE__) . '/include/tool.class.php');
require (dirname(__FILE__) . '/include/json_class.php');

$parent_id = intval($_GET["aid"]);

$sql = $dou->select($dou->table('areas'), '*',' `parent_id` ='.$parent_id);
$data = array();
while($row = $dou->fetch_assoc($sql)){
	$data[] = $row;
}
echo JSON::encode($data);
?>