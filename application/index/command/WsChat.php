<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/7
 * Time: 10:08
 */

namespace app\index\command;


use app\index\model\UserBaseInfo;
use app\index\model\UserClient;
use think\cache\driver\Redis;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class WsChat extends Command
{
    const PORT = 8081;

    private $clientModel;
    private $baseInfoModel;

    public function __construct($name = null)
    {
        parent::__construct();
        $this->clientModel = new UserClient();
        $this->baseInfoModel = new UserBaseInfo();
    }

    protected function configure()
    {
        $this->setName('start_ws_chat')->setDescription('start ws chat ... listen on'.self::PORT);
    }

    protected function execute(Input $input,Output $output)
    {
        $this->startWsChat();
    }

    public function startWsChat(){
        $ws = new \swoole_websocket_server("0.0.0.0", self::PORT);
        $rs = new Redis(['prefix'=>'chat']);
        $ws->set([
                'worker_num' => 3,
                'daemonize' => true,
                'log_file ' => '/var/www/html/myswl/laychat/runtime/chat.log'
            ]);

        $ws->on('open', function ($ws, $request) use($rs){
            echo $request->fd.' connect'."\n";
        });

        $ws->on('message', function ($ws, $frame) use($rs) {
            $data = json_decode($frame->data,true);
            $type = $data['type'];
            switch ($type){
                case 'open':{
                    $userId = $data['id'];
                    $this->boardcastWhenChangeStatus($ws,$userId,'friend_online');
                    $this->clientModel->bindClientIdToUserId($frame->fd,$userId);
                    $unreadInfo = $rs->getNewMessage($userId);
                    if($unreadInfo != false){
                        foreach ($unreadInfo as $k => $v){
                            $this->sendPrivateChat($ws,$frame->fd,$v);
                        }
                    }
                    break;
                }
                case 'msg':{
                    $mine = $data['mine'];
                    $to = $data['to'];
                    $res = $this->clientModel->getClientStatusByUserId($to['id']);
                    $toFd = $res['client_id'];
                    $status = $res['online_status'];
                    if($ws->exist($toFd) == false){
                        if($status == 1){
                            //异常断线,手动下线
                            $this->baseInfoModel->setOnlineStatus($to['id'],0);
                        }
                            //离线先存储到reids
                            $rs->sendSingle($to['id'],$mine);
                    }else{
                        $this->sendPrivateChat($ws,$toFd,$mine);
                    }
                    break;
                }
                default:'';
            }
        });
        //监听WebSocket连接关闭事件
        $ws->on('close', function ($ws, $fd) {
            $userId = $this->clientModel->getUserIdByClientId($fd);
            if($userId != false){
                $this->baseInfoModel->setOnlineStatus($userId,0);
                $this->boardcastWhenChangeStatus($ws,$userId,'friend_offline');
            }
        });

        if($ws){
            echo 'port '.self::PORT.' is listening'."\n";
            $ws->start();
        }else{
            echo 'ws start failed';
        }
    }

    public function sendPrivateChat($ws,$toFd,$mine){
        $mine['timestamp'] = time();
        $mine['mine'] = false;
        $mine['type'] = "friend";
        $msg = [
            'type'=>'msg',
            'data'=>$mine
        ];
        $this->sendHandler($ws,$toFd,$msg);
    }


    public function boardcastWhenChangeStatus($ws,$userId,$type){
        foreach ($ws->connections as $fd) {
            $msg['data'] = ['id'=>$userId];
            $msg['type'] = $type;
            $this->sendHandler($ws,$fd,$msg);
        }
    }

    public function sendHandler($ws,$fd,$msg){
        if($ws->exist($fd) == true){
            $ws->push($fd,json_encode($msg));
        }else{
            $userId = $this->clientModel->getUserIdByClientId($fd);
            $this->baseInfoModel->setOnlineStatus($userId,0);
        }
    }

}