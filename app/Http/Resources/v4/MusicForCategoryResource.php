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
                'slider_id' => $item->id,
                'slider_title' => '333333333333' ,
                'slider_info' => "33333333333333" ,
                'songs_ids' => "1,27,29,28,15,10,3,4,5,2",
                'slider_image' => $item->music_thumbnail_link ,
            ];
        }
    }
}
