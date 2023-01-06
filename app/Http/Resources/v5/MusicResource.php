<?php

namespace App\Http\Resources\v5;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'music_id' => $this->id,
            'music_title' =>($this->music_title),
            'music_file' => route('musics.getLinkYTB',['id'=>$this->music_id_ytb]) ,
            'music_image' => $this->music_thumbnail_link ,
            'music_duration' => gmdate('H:i:s',$this->lengthSeconds) ,
            'playCount' => $this->music_view_count ,
            'like_count' => $this->music_like_count ,
            'is_in_playlist' => 0 ,
            'is_liked' => $this->fav != null ? $this->fav : 0 ,

        ];
    }
}
