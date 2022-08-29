<?php

namespace App\Http\Resources\v3;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicsResource extends JsonResource
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
            'id' => $this->id,
            'songs_title' => $this->music_name ,
            'url_stream' => route('musics.stream',['id'=>$this->uuid]),
            'thumb_img_url' =>   $this->music_image ?  asset('storage/musics/images/'.$this->music_image) : asset('storage/default.png'),
        ];
    }
}
