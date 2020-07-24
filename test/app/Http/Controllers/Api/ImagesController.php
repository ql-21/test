<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandles;
use App\Http\Requests\Api\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImagesController extends Controller
{
    public function store(ImageRequest $request,ImageUploadHandles $imageUpload,Image $image)
    {
        $user=$request->user();
        $size=$request->type == 'avatar'?208:1024;
        $result=$imageUpload->save($request->image,Str::plural($request->type),$user->id,$size);

        $image->path=$result['path'];
        $image->type=$request->type;
        $image->user_id=$user->id;
        $image->save();

        return new ImageResource($image);

    }
}
