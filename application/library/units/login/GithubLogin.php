<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/8
 * Time: 9:47
 */

namespace app\library\units\login;


use login\githublogin\Github;

class GithubLogin implements Login
{
    private $options;
    private $login;

    public function __construct($options)
    {
        $this->options = $options;
        $this->login = new Github($this->options);
    }

    public function login()
    {
        return  $this->login->github_login();
    }

    public function callback()
    {
        return $this->login->github_callback();
    }
}