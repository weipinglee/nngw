<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/2 0002
 * Time: 下午 4:13
 */


class Tool{
    /**
     * 获取当前域名
     *
     * @param bool $http
     * @param bool $entities
     *
     * @return string
     */
    public static function getHttpHost($http = true) {
        $host = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']);

        if ($http)
        {
            $host = 'http://' . $host;
        }

        return $host.dirname($_SERVER['SCRIPT_NAME']);
    }

    /**
     * 获取一个订单号
     * @return string
     */
    public static function getOrderNo(){
        return date('YmdHms').rand(000000,999999);
    }
}
