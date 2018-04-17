<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/22
 * Time: 11:13
 */

namespace app\index\controller;
use app\library\helpers\HttpHelper;
use app\library\helpers\OutputHelper;
use think\Request;

header("Access-Control-Allow-Origin:*");


class Robbot
{
    const NOT_FOUND = '抱歉，没有找到相关信息:)';

    public function autoChat(){
        if(!Request::instance()->isPost()){
            $out = $this->outHandler('err','invalid request');
            return OutputHelper::makeOutput($out);
        }
        $info = input('info');
        $userId = input('userid');
        $config = config('robbot.tuling');
        $key = $config['appkey'];
        $host = $config['apihost'];
        $param = [
            'key'=>$key,
            'info'=>$info,
            'userid'=>$userId
        ];
        $res = HttpHelper::httpPost($host,$param);
        if($res != false){
            $response = json_decode($res,true);
            $code = $response['code'];
            switch ($code){
                case 100000:{
                    $out = $this->outHandler('text',$response['text']);break;
                }
                case 200000:{
                    $out = $this->outHandler('pic',$response['url']);break;
                }
                case 302000:{
                    if(count($response['list'])>0){
                        $out = $this->outHandler('news',$response['list']);
                    }else{
                        $out = $this->outHandler('err',self::NOT_FOUND);
                    }
                    break;
                }
                case 308000:{
                    if(count($response['list'])>0){
                        $out = $this->outHandler('cook',$response['list']);
                    }else{
                        $out = $this->outHandler('err',self::NOT_FOUND);
                    }
                    break;
                }
                default:
                    $out = $this->outHandler('err',$this->randMsg());
            }
            return OutputHelper::makeOutput($out);
        }
    }

    public function outHandler($type,$data){
        return [
            'type'=>$type,
            'data'=>$data
        ];
    }

    public function randMsg(){
        $arr = [
            '您好，我现在有事不在，一会再和您联系。',
            '最惠保 To Be NO.1 ',
            '洗澡中，请勿打扰，偷窥请购票，个体四十，团体八折，订票电话：一般人我不告诉他！face[哈哈] ',
            '你好，我是主人的美女秘书，有什么事就跟我说吧，等他回来我会转告他的。face[心] face[心] face[心] ',
            'face[威武] face[威武] face[威武] face[威武] ',
            '新年快乐<（@￣︶￣@）>',
            '你要和我说话？你真的要和我说话？你确定自己想说吗？你一定非说不可吗？那你说吧，这是自动回复。',
            'face[黑线]  你慢慢说，别急……',
            '(*^__^*) face[嘻嘻] ，是yian吗？'
        ];
        return mt_rand(0,count($arr)-1);
    }
}