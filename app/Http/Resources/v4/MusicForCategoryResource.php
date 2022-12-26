<?php

namespace App\Http\Resources\v4;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicForCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $faker = \Faker\Factory::create();
        foreach ($this->music->random(1) as $item){

            return [
                'slider_id' => $item->id,
                'slider_title' => $faker->name ,
                'slider_info' => $faker->company,
                'songs_ids' => "",
                'slider_image' => $item->music_thumbnail_link ,
            ];
        }
    }
}
