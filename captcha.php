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

require ( 'include/captcha.class.php');

// 开启SESSION
session_start();

$captcha = new Captcha();
$captcha->width  = 78;
$captcha->height = 29;
$captcha->maxWordLength = 4;
		$captcha->minWordLength = 4;
		$captcha->fontSize      = 15;
// 清除之前出现的多余输入
@ob_end_clean();

$captcha->CreateImage();


?>