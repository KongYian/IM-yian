<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/4
 * Time: 16:09
 */

namespace app\library\helpers;


class OutputHelper
{
    public static function makeOutput($data = [] , $code = 0 , $msg = '')
    {
        $rtn['code'] = $code;
        $rtn['msg'] = $msg;
        $rtn['data'] = $data;
        return json_encode($rtn,JSON_UNESCAPED_UNICODE);
    }
}