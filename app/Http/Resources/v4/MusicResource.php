<?php

namespace App\Http\Resources\v4;

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
            'song_id' => $this->id,
            'song_title' => $this->music_title ,
            'song_image' => $this->music_thumbnail_link ,
            'song_info' =>  $this->music_description ,
            'song_lyrics' => $this->music_lyrics ,
            'song_type' => 'local' ,
            'song_url' => route('musics.getLinkYTB',['id'=>$this->music_id_ytb]) ,
            'views' => $this->music_view_count ,
            'downloads' => $this->music_view_count,
            'total_rate' => rand(3,5) ,
            'favourite' => $this->fav != null ? $this->fav : false ,

        ];
    }
}
