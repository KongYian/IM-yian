<?php
namespace app\index\controller;
header("Access-Control-Allow-Origin:*");

use app\index\model\UserBaseInfo;
use app\library\filters\authenticate\ValidLoginFilter;
use app\library\helpers\OutputHelper;
use app\library\units\Upload;
use think\cache\driver\Redis;
use think\Controller;
use think\Log;
class Index extends Controller
{
    public $filter;

    protected $beforeActionList = [
        'authenticate'=>[
            'only'=>'index'
        ]
    ];

    public function authenticate(){
        $id = input('id');
        $this->filter = new ValidLoginFilter;
        $this->filter->checkLoginStatus($id);
    }

    public function index()
    {
        $id = input('id');
        $userModel = new UserBaseInfo();
        $mine = $userModel->getMineInfo($id);
        if($mine === false){
            $this->redirect('index/login/index');
        }
        $friendsList = $userModel->getFriendList($id);
        $this->assign([
            'mine' => $mine,
            'friendsList' => $friendsList
        ]);
        return $this->fetch('index');
    }

    public function uploadImage(){
        Log::info('upload...');
        Log::info($_FILES);
        Log::info('end');
        $upload = new Upload();
        $src = $upload->upload();
        return OutputHelper::makeOutput(['src'=>$src]);
    }


}
