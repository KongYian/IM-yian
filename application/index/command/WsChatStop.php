<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/8
 * Time: 15:06
 */

namespace app\index\command;


use app\index\model\UserBaseInfo;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class WsChatStop extends Command
{
    protected function configure()
    {
        parent::configure(); // TODO: Change the autogenerated stub
        $this->setName('stop_ws_chat')->setDescription('stop ws chat ...');
    }

    public function execute(Input $input, Output $output)
    {
        exec("ps -eaf |grep 'start_ws_chat' | grep -v 'grep'| awk '{print $2}'|xargs kill -9");
        $baseinfo = new UserBaseInfo();
        $baseinfo->setAllUseroffline();
    }
}