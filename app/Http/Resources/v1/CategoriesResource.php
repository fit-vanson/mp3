<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)   {

        dd($this);
        return [
            'category_id' => $this->id,
            'category_name' => $this->category_name,
            'total_music'=>$this->music_count,
            'category_image' =>  asset('storage/musics/images/'.$this->category_image),
        ];
    }
}
