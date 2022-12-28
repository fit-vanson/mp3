<?php

namespace App\Http\Resources\v4;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicFeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this->categories[rand(0,count($this->categories)-1)]);
        return [
            'slider_id' => $this->categories[rand(0,count($this->categories)-1)]->id,
            'slider_title' => ($this->music_title) ,
            'slider_info' => substr($this->music_description,0,30),
            'songs_ids' => "",
            'slider_image' => $this->music_thumbnail_link ,
        ];
    }
}
