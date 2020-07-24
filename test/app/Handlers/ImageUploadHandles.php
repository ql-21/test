<?php


namespace App\Handlers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * 图片存储工具
 * Class ImageUploadHandles
 * @package App\Handlers
 */
class ImageUploadHandles
{
    //只允许图片上传上传的后缀
    protected $allowed_ext = ["png","jpg","gif","jpeg"];


    public function save(UploadedFile $file,$folder,$file_prefix,$max_width=false)
    {

        $disk=Storage::disk(env('FILESYSTEM_DRIVER'));

        $folder_path=$folder.'/'.date('Ym/d',time());

        $path=$disk->put($folder_path,$file);

        return [
            'path'=>$disk->url($path)
        ];


    }

    public function reduceSize($file_path,$max_width){
        $image=Image::make($file_path);
        $image->resize($max_width,null,function ($constraint){
            //等比例缩放
            $constraint->aspectRatio();

            //防止图片变大
            $constraint->upsize();
        });

        $image->save();

    }

}