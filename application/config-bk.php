 <?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    'host'                   => 'http://chat.blueyian.top/',
    'app_debug'              => true,
    'app_trace'              => false,
    'login'=>[
        'qqlogin'=>[
            'appid'     =>      '',
            'callback'  =>      'http://chat.blueyian.top/index.php/Index/Login/callbackQq',
            'scope'     =>      'blueyian',
            'appkey'    =>      ''
        ],
        'baidulogin'=>[
            'appid'     =>      '',
            'appkey'    =>      'Y7sSvuqR9swWTnwfy5lkNBDrPd58TjjQ',
            'callback'  =>      'http://chat.blueyian.top/index/login/callbackBaidu',
            'scope'     =>      '',
        ],
        'githublogin'=>[
            'appid'     =>      '',
            'appkey'    =>      '',
            'callback'  =>      'http://chat.blueyian.top/index/login/callbackGithub',
            'scope'     =>      'blueyian',
            'state'     =>      'blueyian',
        ],
        'URL_MODEL' => '2'

    ]
];
