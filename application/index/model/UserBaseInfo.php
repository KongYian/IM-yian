<?php

/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/4
 * Time: 14:50
 */
namespace app\index\model;

use think\Db;
use think\Model;

class UserBaseInfo extends Model
{
    protected $resultSetType = '';

    public function getMineInfo($id = 0){
        $mine = $this->where(['id'=>$id])->find();
        if($mine){
            return $mine->getData();
        }else{
            return false;
        }
    }

    public function getFriendList($id = 0){
        return Db::table('user_base_info')
            ->field([
                'id',
                'nickname as username',
                'avatar',
                'sign',
                "if(online_status=1,'online','offline') as status"
            ])
            ->where(['lock_status'=>1])
            ->where(['id'=>['<>',$id]])
            ->select();
    }

    public function setOnlineStatus($userId,$status = 1){
        $this->where(['id'=>$userId])->update(['online_status'=>$status]);
    }


    public function initUserInfo($info = [],$platform = 0){
        switch ($platform){
            case 1 :{
                $openid = $info['openid'];
                $insert = [
                    'nickname'=>$info['nickname'],
                    'avatar'=>$info['figureurl'],
                    'openid'=>$openid,
                    'sign'=>$this->getRandSign(),
                    'platform'=>$platform
                ];
                return $this->saveNewUser($openid,$platform,$insert);
            }
            case 2 :{}
            //baidu
            case 3 :{
                $openid = $info['uid'];
                $insert = [
                    'nickname'=>$info['uname'],
                    'avatar'=>'http://tb.himg.baidu.com/sys/portraitn/item/'.$info['portrait'],
                    'openid'=>$openid,
                    'sign'=>$this->getRandSign(),
                    'platform'=>$platform
                ];
                return $this->saveNewUser($openid,$platform,$insert);
            }
            //github
            case 4 :{
                $openid = $info['id'];
                $insert = [
                    'nickname'=>$info['login'],
                    'avatar'=>$info['avatar_url'],
                    'openid'=>$openid,
                    'sign'=>$this->getRandSign(),
                    'platform'=>$platform
                ];
                return $this->saveNewUser($openid,$platform,$insert);
            }
        }
    }

    public function saveNewUser($openid,$platform,$insert){
        $res = $this->field('id')->where(['openid'=>$openid])->where(['platform'=>$platform])->find();
        if($res){
            $res = $res->getData();
            $this->where(['id'=>$res['id']])->update($insert);
            return $res['id'];
        }else{
            $this->insert($insert);
            return $this->getLastInsID();
        }
    }

    public function setAllUseroffline(){
        $this->where(['online_status'=>1])->update(['online_status'=>0]);
    }

    public function getRandSign(){
        $arr =  [
            '常记溪亭日暮，沉醉不知归路',
            '有花堪折直须折，莫待无花空折枝',
            '昨夜雨疏风骤，浓睡不消残酒',
            '苟以国家生死以，岂因祸福避趋之',
            '桃之夭夭，灼灼其华',
            '怨无大小，生于所爱'
        ];
        return $arr[mt_rand(0,10)];
    }

}