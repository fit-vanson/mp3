<?php

namespace App\Http\Resources\v6;

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

        $path = storage_path('app/public/wallpapers/'.$this->wallpaper_image);
        $pathThumb = storage_path('app/public/wallpapers/thumbnails/'.$this->wallpaper_image);

        if (file_exists($path)){
            $image = getimagesize($path);
            $size = filesize($path);
        }

        if (file_exists($pathThumb)){
            $imageThumb = getimagesize($pathThumb);
            $sizeThumb  = filesize($pathThumb);
        }

        return [
            'id' => $this->id,
            'detail' => null,
            'author' => null,
            'website' => null,
            'price' => 0,
            'content_type' => 'single_image',
            'priority' => 10000,
            'trending_priority' => 10000,
            'price_type' => 'FREE',
            'content' => [
                'size' => $size,
                'mimetype' => $image['mime'],
                'file_name' => $this->wallpaper_image,
                'path' => '/../../storage/wallpapers/'.$this->wallpaper_image,
            ],
            'thumb' => [
                'size' => $sizeThumb,
                'mimetype' => $imageThumb['mime'],
                'file_name' => $this->wallpaper_image,
                'path' => '/../../storage/wallpapers/thumbnails/'.$this->wallpaper_image,
            ],
            'download' => $this->wallpaper_download_count,
            'set_wallpaper' => $this->wallpaper_view_count,
            'category_id' => $this->wallpaper_view_count,
            'category_name' => $this->wallpaper_view_count,
            'download_in_period_time' => $this->wallpaper_view_count,



        ];
    }
}
