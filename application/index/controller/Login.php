<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/7
 * Time: 15:39
 */

namespace app\index\controller;


use app\index\model\UserBaseInfo;
use app\library\filters\authenticate\ValidLoginFilter;
use app\library\units\login\BaiduLogin;
use app\library\units\login\GithubLogin;
use app\library\units\login\QqLogin;
use think\Controller;
use think\Request;

class Login extends Controller
{

    private $filter;

    public function __construct()
    {
        parent::__construct();
        $this->filter = new ValidLoginFilter();
    }

    public function index(){
        return $this->fetch('index');
    }

    public function login(){
        $type = input('get.type');
        switch ($type){
            case 'qq':{
                $qq = new QqLogin(config('login.qqlogin'));
                $url = $qq->login();
                break;
            }
            case 'baidu':{
                $baidu = new BaiduLogin(config('login.baidulogin'));
                $url =  $baidu->login();
                $this->redirect($url);
                break;
            }
            case 'github':{
                $github = new GithubLogin(config('login.githublogin'));
                $url =  $github->login();
                $this->redirect($url);
                break;
            }
            default:$url = 'index/login/index';
        }
        $this->redirect($url);
    }

    public function callbackQq(){
        $qq = new QqLogin(config('login.qqlogin'));
        $info = $qq->callback();
        if(isset($info['openid'])){
            $this->callbackHandler($info,1);
        }
    }


    public function callbackBaidu(){
        $baidu = new BaiduLogin(config('login.baidulogin'));
        $info = $baidu->callback();
        if(isset($info['uid'])){
            $this->callbackHandler($info,3);
        }
    }

    public function callbackGithub(){
        $github = new GithubLogin(config('login.githublogin'));
        $info = $github->callback();
        if(isset($info['id'])){
            $this->callbackHandler($info,4);
        }
    }

    public function callbackHandler($info,$type){
        $userModel = new UserBaseInfo();
        $userId = $userModel->initUserInfo($info,$type);
        $this->filter->saveLoginStatus($userId);
        return $this->redirect('index/index/index',['id'=>$userId]);
    }
}