<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    protected $hiddenFields=[];
    protected $isSensitiveFields=false;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->resource->makeHidden($this->hiddenFields);
        $data = parent::toArray($request);
        return $data;

    }

    public function addHiddenFields($fields)
    {
        $this->hiddenFields=$fields;
        return $this;
    }


}
