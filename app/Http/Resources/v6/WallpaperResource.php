<?php

namespace App\Http\Resources\v6;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
        $image = $imageThumb = null;
        $size = $sizeThumb = 0;

        try {
            if (file_exists($path)){
                $image = getimagesize($path);
                $size = filesize($path);
            }

            if (file_exists($pathThumb)){
                $imageThumb = getimagesize($pathThumb);
                $sizeThumb  = filesize($pathThumb);
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() .'--: '.$this->wallpaper_image. ' ----' . $exception->getLine());
        }

        $category = (new CategoriesResource($this->categories))->first();
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
                'size' => $size ,
                'mimetype' => $image ? $image['mime'] : '',
                'file_name' => $this->wallpaper_image,
                'path' => '/../../storage/wallpapers/'.$this->wallpaper_image,
            ],
            'thumb' => [
                'size' => $sizeThumb,
                'mimetype' => $imageThumb ? $imageThumb['mime'] : '',
                'file_name' => $this->wallpaper_image,
                'path' => '/../../storage/wallpapers/thumbnails/'.$this->wallpaper_image,
            ],
            'download' => $this->wallpaper_download_count,
            'set_wallpaper' => $this->wallpaper_view_count,
            'category_id' => $category->category_id,
            'category_name' => $category->category_name,
            'download_in_period_time' => rand(500,2000),
        ];
    }
}
