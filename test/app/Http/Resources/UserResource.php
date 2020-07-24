<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $isSensitiveFields=false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if(!$this->isSensitiveFields){
            $this->resource->addHidden(['phone','email','nickname']);
        }

        $data= parent::toArray($request);

        $data['bound_phone']=$this->resource->phone?true:false;
        $data['bound_wechat']=($this->resource->weixin_openid||$this->resource->weixin_unionid)?true:false;

        return $data;
    }

    public function showSensitiveFields(){
        $this->isSensitiveFields=true;

        return $this;
    }
}
