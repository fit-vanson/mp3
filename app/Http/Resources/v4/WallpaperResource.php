<?php

namespace App\Http\Resources\v4;

use Illuminate\Http\Resources\Json\JsonResource;

class WallpaperResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tags = [];
        foreach ($this->tags as $tag){
            $tags[] = $tag->tag_name;
        }
        return [
            'id' =>$this->id,
            'image' => asset('storage/wallpapers/'.$this->wallpaper_image),
            'type' =>$this->image_extension == 'image/jpeg' ? 'IMAGE' : 'GIF'  ,
            'premium' => 0,
            'tags' => implode(",", $tags),
            'view' =>$this->wallpaper_view_count,
            'download' =>$this->wallpaper_download_count,
            'like' =>$this->wallpaper_like_count,
        ];
    }
}
