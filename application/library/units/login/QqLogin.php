<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/7
 * Time: 16:04
 */

namespace app\library\units\login;

use login\qqlogin\QC;

class QqLogin implements Login
{

    private $options;
    private $login;

    public function __construct($options)
    {
        $this->options = $options;
        $this->login = new QC($this->options);
    }


    public function login()
    {
        $url = $this->login->qq_login();
        return $url;
    }

    public function callback()
    {
        $access_token = $this->login->qq_callback();
        $openid = $this->login->get_openid();
        $res = $this->login->getUserInfo($openid,$access_token);
        $res['openid'] = $openid;
        return $res;
    }
}