<?php

namespace App\Http\Resources\v0;

use \App\Http\Resources\v0\CategoriesResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Rolandstarke\Thumbnail\Facades\Thumbnail;

class WallpapersResource extends JsonResource
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
            'categories' =>
                CategoriesResource::collection($this->categories),
//                array(new CategoriesResource($this->categories)),
            'id' => $this->id,
            'name' => $this->wallpaper_name ,
            'thumbnail_image' => Thumbnail::src('/wallpapers/'.$this->wallpaper_image, 'public')->smartcrop(360, 640)->url(),
            'detail_image' => Thumbnail::src('/wallpapers/'.$this->wallpaper_image, 'public')->smartcrop(360, 640)->url(),
            'download_image' => asset('storage/wallpapers/'.$this->wallpaper_image),
            'like_count' => $this->wallpaper_like_count,
            'views' => $this->wallpaper_view_count,
            'feature' => $this->wallpaper_feature,
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
