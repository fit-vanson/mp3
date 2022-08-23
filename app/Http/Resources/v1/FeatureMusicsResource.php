<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureMusicsResource extends JsonResource
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
            foreach ($item->tags as $tag){
                $tags[] = $tag->tag_name;
            }
            return [
                'liked' => $item->liked ? : 0,
                'id' => $item->id,
                'name' => $item->music_name ,
                'image' => asset('storage/musics/images/'.$item->music_image),
                'file' => asset('storage/musics/files/'.$item->music_file),
                'tags' => implode(",", $tags),
                'download_count' => $item->music_download_count,
                'like_count' => $item->music_like_count,
                'view_count' => $item->music_view_count,
                'feature' => $item->music_feature,
                'link' => $item->music_link,
                'created_at' => $item->created_at->format('d/m/Y'),
            ];
        }
    }
}
