<?php

namespace App\Http\Resources\v1;

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
            'id' => $this->id,
            'name' => $this->music_name ,
            'image' => asset('storage/musics/images/'.$this->music_image),
            'file' => asset('storage/musics/files/'.$this->music_file),
            'tags' => implode(",", $tags),
            'download_count' => $this->music_download_count,
            'like_count' => $this->music_like_count,
            'view_count' => $this->music_view_count,
            'feature' => $this->music_feature,
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
