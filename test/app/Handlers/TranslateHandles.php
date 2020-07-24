<?php


namespace App\Handlers;


use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Overtrue\Pinyin\Pinyin;

class TranslateHandles
{

    /**
     * [slug] 翻译slug
     * @param $str
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function slug($str){
        $result=$this->baidu($str);
        //判断返回结果
        if(isset($result['trans_result'][0]['dst'])){
            return Str::slug($result['trans_result'][0]['dst']);
        }else{
            $pinyin=new Pinyin();
            return Str::slug($pinyin->permalink($str,''));
        }

    }

    /**
     * [baidu]百度翻译
     * @param $str
     * @param string $langage
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function baidu($str,$langage='en'){
        $client=new Client();

        //百度appid,秘钥
        $baidu_appid=config('services.baidu_translate.appid');
        $baidu_key=config('services.baidu_translate.key');
        //随机数
        $salt=time();
        //签名
        $sign=md5($baidu_appid.$str.$salt.$baidu_key);
        //请求参数
        $form_params=[
            'q'=>$str,
            'from'=>'auto',
            'to'=>$langage,
            'appid'=>$baidu_appid,
            'salt'=>$salt,
            'sign'=>$sign,
        ];
//        $result=$client->get(''.$form_params);
        $result=$client->request('POST','http://api.fanyi.baidu.com/api/trans/vip/translate?',[
            'form_params'=>$form_params
        ]);

        $result=json_decode($result->getBody(),true);

        return $result;

    }
}