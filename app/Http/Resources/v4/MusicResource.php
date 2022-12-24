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
            'song_title' => "slider_title",
            'slider_image' => $this->music_thumbnail_link ,
            'song_info' => "11111111" ,
            'song_lyrics' => "33333333333333" ,
            'song_type' => 'local' ,
            'song_url' => $this->music_url_link_audio_ytb ,
            'views' => "$this->music_view_count" ,
            'downloads' => "$this->music_view_count" ,
            'total_rate' => rand(3,5) ,
            'favourite' => "false" ,
//            'artist_list' => [] ,
        ];
    }
}
