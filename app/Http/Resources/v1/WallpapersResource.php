<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

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

        $path = storage_path('app/public/wallpapers/'.$this->wallpaper_image);
        $image = $size = '';
        if (file_exists($path)){
            $image = getimagesize($path);
            $size = $this->filesize_formatted($path);
        }
        $categories = (new CategoriesResource($this->categories));
        return [
            'image_id' => $this->id,
            'image_name' => $this->wallpaper_name,
            'image_upload' => $this->wallpaper_name,
            'image_url' =>  '',
            'type' => 'upload',
            'resolution' =>$image ?  $image[0]. ' x '.$image[1]: 'n/a',
            'size' => $size ? $size : 'n/a',
            'mime' => $image ?  $image['mime'] : 'n/a',
            'views' => $this->wallpaper_view_count,
            'downloads' => $this->wallpaper_like_count,
            'featured' => $this->wallpaper_feature == 0 ? 'yes':'no',
            'tags' => $categories ? $categories[0]->category_name: '',
            'category_id' => $categories ? $categories[0]->id: '',
            'category_name' => $categories ? $categories[0]->category_name: '',
            'last_update' => $this->updated_at,
        ];
    }

    function filesize_formatted($path)
    {
        $size = filesize($path);
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}
