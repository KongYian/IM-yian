<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/7
 * Time: 14:39
 */

namespace app\index\model;


use think\Db;
use think\Log;
use think\Model;

class UserClientZhb extends Model
{
    public function bindClientIdToUserId($clientId,$userId){
        $exist = $this->where(['user_id'=>$userId])->find();
        if(!$exist){
            $this->insert(['user_id'=>$userId,'client_id'=>$clientId]);
        }else{
            $this->where(['user_id'=>$userId])->update(['client_id'=>$clientId]);
        }
    }


    public function getClientStatusByUserId($userId){
        $res = $this->field('a.client_id,b.online_status')
            ->alias('a')
            ->join('user_base_info b','a.user_id = b.id')
            ->where(['a.user_id'=>$userId])
            ->find();
        if($res){
            return $res->getData();
        }else{
            return false;
        }
    }

    public function getUserIdByClientId($fd){
        $res = $this->field('user_id')->where(['client_id'=>$fd])->find();
        if($res){
            $data = $res->getData();
            return $data['user_id'];
        }else{
            return false;
        }
    }

    public function getClientIdByUserId($userId){
        $res = Db::table('user_client_zhb')->field('client_id')->where(['user_id'=>$userId])->find();
        return $res['client_id'];
    }
}