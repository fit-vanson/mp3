<?php

namespace App\Http\Resources\v2;

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
        foreach ($this->tags as $tag){
            $tags[] = $tag->tag_name;
        }
        return [
            'liked' => $this->liked ? : 0,
            'id' => $this->uuid,
            'name' => $this->music_name ,
            'image' => asset('storage/musics/images/'.$this->music_image),
            'file' => route('musics.stream',['id'=>$this->uuid]),
            'tags' => implode(",", $tags),
            'download_count' => $this->music_download_count,
            'like_count' => $this->music_like_count,
            'view_count' => $this->music_view_count,
            'feature' => $this->music_feature,
            'link' => $this->music_link,
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
