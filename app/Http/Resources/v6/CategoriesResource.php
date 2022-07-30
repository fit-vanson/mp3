<?php

namespace App\Http\Resources\v6;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $path = storage_path('app/public/categories/'.$this->category_image);
        $image =  '';
        if (file_exists($path)){
            $image = getimagesize($path);
            $size = filesize($path);
        }
        return [
            'id' => $this->id,
            'name' => $this->category_name,
            'total_wallpapers'=>$this->wallpaper_count,
            'thumb'=> [
                'size' => $size,
                'mimetype' => $image['mime'],
                'file_name' => $this->category_image,
                'path' => '/../../storage/categories/'.$this->category_image,
            ]


        ];
    }
}
