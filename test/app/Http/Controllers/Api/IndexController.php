<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\IndexResource;
use App\Models\Banner;


class IndexController extends Controller
{
    /**
     * [banners]轮播图
     * @param Banner $banner
     */
    public function banners(Banner $banner)
    {
        $banners=$banner->all();
        return  IndexResource::collection($banners);
    }
}
