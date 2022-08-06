<?php

namespace App\Http\Resources\v0;


use Illuminate\Http\Resources\Json\JsonResource;

class FeatureWallpaperResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->wallpaper_count > 0 ){
            foreach ($this->wallpaper->where('image_extension', '<>', 'image/gif')->random(1) as $item){
                return [
                    'categories' =>
                        array(new CategoriesResource($this)),
                    'id' => $item->id,
                    'name' => $item->wallpaper_name ,
                    'thumbnail_image' => asset('storage/wallpapers/thumbnails/'.$item->wallpaper_image),
                    'detail_image' => asset('storage/wallpapers/thumbnails/'.$item->wallpaper_image),
                    'download_image' => asset('storage/wallpapers/'.$item->wallpaper_image),
                    'like_count' => $item->wallpaper_like_count,
                    'views' => $item->wallpaper_view_count,
                    'feature' => $item->wallpaper_feature,
                    'created_at' => $item->created_at->format('d/m/Y'),
                ];
            }
        }

    }
}
