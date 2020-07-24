<?php

return [
        //http请求超时时间
        'timeout'=>10,

        //默认发送配置
        'default'=>[
            //网关调用策略，默认：顺序调用
            'strategy'=> \Overtrue\EasySms\Strategies\OrderStrategy::class,

            //默认可用的发送网关
            'gateways'=>[
                'aliyun',
            ],
        ],

        'gateways'=>[
            'errorlog'=>[
                'file'=>'/tmp/easy-sms.log',
            ],
            'aliyun'=>[
                'access_key_id'=>env('SMS_ALIYUN_ACCESS_KEY_ID'),
                'access_key_secret'=>env('SMS_ALIYUN_ACCESS_KEY_SECRET'),
                'sign_name'=> '县域视窗',
                'templates'=>[
                    'register'=> env('SMS_ALIYUN_TEMPLATE_REGISTER')
                ]
            ],
        ],

    ];