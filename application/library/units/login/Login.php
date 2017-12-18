<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/7
 * Time: 16:03
 */

namespace app\library\units\login;


interface Login
{
    public function login();
    public function callback();
}