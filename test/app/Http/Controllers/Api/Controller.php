<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    /**
     * [errorResponse] 错误返回信息
     * @param $statusCode
     * @param null $message
     * @param int $code
     */
    public function errorResponse($statusCode,$message=null,$code=0)
    {
        throw new HttpException($statusCode,$message,null,[],$code);
    }

}
