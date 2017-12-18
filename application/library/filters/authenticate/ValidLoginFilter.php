<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/17
 * Time: 11:32
 */

namespace app\library\filters\authenticate;


use think\Controller;

class ValidLoginFilter extends Controller
{
    const LOGIN_NAME = 'bluechat';

    public static function getLoginName($id){
        return self::LOGIN_NAME.$id;
    }

    public function saveLoginStatus($id){
        session(static::getLoginName($id),$id);
    }

    public function checkLoginStatus($id){
        $session = session(static::getLoginName($id));
        if(isset($session)){
            return true;
        }else{
            $this->redirect('index/login/index');
        }
    }
}