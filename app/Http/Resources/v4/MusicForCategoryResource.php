<?php

namespace App\Http\Resources\v4;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicForCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        foreach ($this->music->random(1) as $item){
            return [
                'slider_id' => $this->id,
                'slider_title' => ($item->music_title) ,
                'slider_info' => substr(($item->music_title),0,30),
                'songs_ids' => "",
                'slider_image' => $item->music_thumbnail_link ,
            ];
        }
    }
}
