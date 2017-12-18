<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/8
 * Time: 9:47
 */

namespace app\library\units\login;


use login\baidulogin\Baidu;

class BaiduLogin implements Login
{
    private $options;
    private $login;

    public function __construct($options)
    {
        $this->options = $options;
        $this->login = new Baidu($this->options);
    }

    public function login()
    {
        return  $this->login->baidu_login();
    }

    public function callback()
    {
        return $this->login->baidu_callback();
    }
}