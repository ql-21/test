<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $data = parent::toArray($request);
        $data['tag'] = (new TagResource($this->whenLoaded('tag')))->addHiddenFields('pivot');
        $data['user']=$this->userData();
        return $data;
    }

    /**
     * [userData] 用户返回资源过滤
     * @return array
     */
    public function userData()
    {
        $userData=new UserResource($this->whenLoaded('user'));
        $data=[
            'id'=>$userData['id'],
            'name'=>$userData['name'],
            'avatar'=>$userData['avatar'],
        ];
        return $data;
    }

}
