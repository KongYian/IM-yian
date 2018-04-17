<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/22
 * Time: 11:15
 */

namespace app\library\helpers;


class HttpHelper
{
    public static function httpPost($query_url,$params){
        $headers = [
            "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
            "Accept-Encoding:gzip, deflate",
            "Accept-Language:zh-CN,zh;q=0.9",
            "Connection:keep-alive",
            "Upgrade-Insecure-Requests:1",
            "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $query_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($ch);
        curl_close($ch);
        return $resp;
    }
}