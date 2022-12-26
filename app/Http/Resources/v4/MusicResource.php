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

        $faker = \Faker\Factory::create();

        return [
            'song_id' => $this->id,
            'song_title' => $faker->name,
            'slider_image' => $this->music_thumbnail_link ,
            'song_info' => $faker->address ,
            'song_lyrics' => $faker->paragraph ,
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
