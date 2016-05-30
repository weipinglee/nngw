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
if (!defined('IN_DOUCO')) {
    die('Hacking attempt');
}
class Upload {
    var $images_dir;
    var $thumb_dir;
    var $upfile_type; // 上传的类型，只允许 doc,docx
    var $upfile_size_max = 2048 ; // 上传大小限制，单位是“KB”，默认为：2048KB
    var $jianli_type = array('doc','docx');
    var $jianli_size_max  = 2097152;//1024 * 1024 *2 即2048KB 即2048*1024字节
    var $to_file = true; // $this->to_file设定为false时将以原图文件名创建缩略图
    
    /**
     * +----------------------------------------------------------
     * 构造函数
     * $images_dir 文件上传路径
     * $thumb_dir 缩略图路径
     * +----------------------------------------------------------
     */
    function Upload($images_dir = '../upload/', $thumb_dir = 'thumb/', $upfile_type = 'jpg,gif,png', $upfile_size_max = '2048') {
        $this->images_dir = $images_dir; // 文件上传路径 结尾加斜杠
        $this->thumb_dir = $thumb_dir; // 缩略图路径（相对于$images_dir） 结尾加斜杠，留空则跟$images_dir相同
        $this->upfile_type = $upfile_type;
        $this->upfile_size_max = $upfile_size_max;
    }
    /**
     * +----------------------------------------------------------
     *  判断上传文件和大小 是否正确
     * @param string $fileName  上传的文件 即 $_FILES['fil']
     * +----------------------------------------------------------
     */
    function jian( $file = '') {
        if(empty($file)){
            return 0; //返回 0  文件不存在
        }
        $ext = substr($file['name'], strrpos($file['name'], '.') + 1 );
         //var_dump($this->jianli_type);exit;
        if(!in_array($ext, $this->jianli_type)){
            return 2;// 返回 2  文件类型不正确
        }
        if( $file['size'] > $this->jianli_size_max){
            return 3;// 返回 3 文件尺寸过大
        }
        return 1;// 返回1  正确
    }


    /**
     * +----------------------------------------------------------
     * 图片上传的处理函数
     * $upfile 上传的图片域
     * $image_name 给上传的图片重命名
     * +----------------------------------------------------------
     */
    function upload_image($upfile, $image_name = '') {
        if ($GLOBALS['dou']->dir_status($this->images_dir) != 'write') {
            $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['upload_dir_wrong']);
        }
        
        // 没有命名规则默认以时间作为文件名
        if (empty($image_name)) {
            $image_name = md5(microtime(true).rand(100000, 999999)); // 设定当前时间为图片名称
        }
        
        if (@ empty($_FILES[$upfile]['name'])) {
            $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['upload_image_empty']);
        }
        $name = explode(".", $_FILES[$upfile]["name"]); // 将上传前的文件以“.”分开取得文件类型
        $img_count = count($name); // 获得截取的数量
        $img_type = $name[$img_count - 1]; // 取得文件的类型
        if (stripos($this->upfile_type, $img_type) === false) {
            $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['upload_file_support'] . $this->upfile_type . $GLOBALS['_LANG']['upload_file_support_no'] . $img_type);
        }
        $photo = $image_name . "." . $img_type; // 写入数据库的文件名
        $upfile_name = $this->images_dir . $photo; // 上传后的文件名称

        // echo $upfile_name;exit;
        $upfile_ok = move_uploaded_file($_FILES[$upfile]["tmp_name"], $upfile_name);
        if ($upfile_ok) {
            $img_size = $_FILES[$upfile]["size"];
            $img_size_kb = round($img_size / 1024);
            if ($img_size_kb > $this->upfile_size_max) {
                @unlink($upfile_name);
                $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['upload_out_size'] . $this->upfile_size_max . "KB");
            }
        } else {
            $GLOBALS['_LANG']['upload_wrong'] = preg_replace('/d%/Ums', $upfile_size_max, $GLOBALS['_LANG']['upload_wrong']);
            $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['upload_wrong']);
        }
        return $photo;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取上传图片信息
     * $photo 原始图片
     * +----------------------------------------------------------
     */
    function get_img_info($photo) {
        $photo = $this->images_dir . $photo;
        $image_size = getimagesize($photo);
        $img_info["width"] = $image_size[0];
        $img_info["height"] = $image_size[1];
        $img_info["type"] = $image_size[2];
        $img_info["name"] = basename($photo);
        $img_info["ext"] = pathinfo($photo, PATHINFO_EXTENSION);
        return $img_info;
    }
    
    /**
     * +----------------------------------------------------------
     * 创建图片的缩略图
     * $photo 原始图片
     * $width 缩略图宽度
     * $height 缩略图高度
     * $quality 生成缩略图片质量
     * +----------------------------------------------------------
     */
    function make_thumb($photo, $width = '128', $height = '128', $quality = '90') {
        $img_info = $this->get_img_info($photo);
        $photo = $this->images_dir . $photo; // 获得图片源
        $thumb_name = substr($img_info["name"], 0, strrpos($img_info["name"], ".")) . "_thumb." . $img_info["ext"]; // 缩略图名称
        if ($img_info["type"] == 1) {
            $img = imagecreatefromgif($photo);
        } elseif ($img_info["type"] == 2) {
            $img = imagecreatefromjpeg($photo);
        } elseif ($img_info["type"] == 3) {
            $img = imagecreatefrompng($photo);
        } else {
            $img = "";
        }
        
        if (empty($img)) {
            return False;
        }
        
        if (function_exists("imagecreatetruecolor")) {
            $new_thumb = imagecreatetruecolor($width, $height);
            ImageCopyResampled($new_thumb, $img, 0, 0, 0, 0, $width, $height, $img_info["width"], $img_info["height"]);
        } else {
            $new_thumb = imagecreate($width, $height);
            ImageCopyResized($new_thumb, $img, 0, 0, 0, 0, $width, $height, $img_info["width"], $img_info["height"]);
        }
        
        // $this->to_file设定为false时将以原图文件名创建缩略图
        if ($this->to_file) {
            if (file_exists($this->images_dir . $this->thumb_dir . $thumb_name))
                @ unlink($this->images_dir . $this->thumb_dir . $thumb_name);
            ImageJPEG($new_thumb, $this->images_dir . $this->thumb_dir . $thumb_name, $quality);
            return $this->images_dir . $this->thumb_dir . $thumb_name;
        } else {
            ImageJPEG($new_thumb, '', $quality);
        }
        ImageDestroy($new_thumb);
        ImageDestroy($img);
        return $thumb_name;
    }

    /**
     * +----------------------------------------------------------
     * 上传简历（新增）
     * 
     * +----------------------------------------------------------
    */
    function createDir() {
        $path = 'data/upload/'.date('Y/m/d');
        $abs = ROOT.$path;
        if(is_dir($ads) || mkdir($abs , 0777, true)) {
            return $path;
        } else {
            return false;
        }
    }

    //生成随机字符串
    function randStr($length = 6) {
        $str = str_shuffle('ABCDEFGHJKMNPQRSTUVWXYabcdefghjkmnpqrstuvwxy23456789');
        $str = substr($str , 0, $length);
        return $str;
    }

    //获取文件后缀
    function getExt($name) {
        return strrchr($name , '.');
    }




}
?>