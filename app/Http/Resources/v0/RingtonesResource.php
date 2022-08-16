<?php

namespace App\Http\Resources\v0;

use Illuminate\Http\Resources\Json\JsonResource;

class RingtonesResource extends JsonResource
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
            'liked' => $this->liked?:0,
            'categories' =>
                CategoriesResource::collection($this->categories),
            'id' => $this->id,
            'name' => $this->ringtone_name,
            'thumbnail_image' => asset('storage/ringtones/'.$this->thumbnail_image),
            'ringtone_file'=>asset('storage/ringtones/'.$this->ringtone_file),
            'like_count' => $this->ringtone_like_count,
            'views' => $this->ringtone_view_count,
            'downloads' => $this->ringtone_download_count,
            'feature' => $this->ringtone_feature,
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
