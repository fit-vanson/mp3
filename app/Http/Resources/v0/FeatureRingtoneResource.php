<?php

namespace App\Http\Resources\v0;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureRingtoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        foreach ($this->ringtone->random(1) as $item){
            return [
                'categories' =>
                    array(new CategoriesResource($this)),
                'id' => $item->id,
                'name' => $item->ringtone_name,
                'thumbnail_image' => asset('storage/ringtones/'.$item->thumbnail_image),
                'ringtone_file'=>asset('storage/ringtones/'.$item->ringtone_file),
                'like_count' => $item->ringtone_like_count,
                'views' => $item->ringtone_view_count,
                'downloads' => $item->ringtone_download_count,
                'feature' => $item->ringtone_feature,
                'created_at' => $item->created_at->format('d/m/Y'),
            ];
        }
    }
}
