<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2017/12/12
 * Time: 9:49
 */

namespace app\library\units;


use think\Log;

class Upload
{
    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                return config('host').'uploads/'.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                return false;
            }
        }
    }
}