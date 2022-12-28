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
            'slider_id' => 1,
            'slider_title' => ($this->music_title) ,
            'slider_info' => $this->music_title,
            'songs_ids' => "",
            'slider_image' => $this->music_thumbnail_link ,
        ];
    }
}
