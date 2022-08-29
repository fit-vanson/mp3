<?php

namespace App\Http\Resources\v3;

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
        foreach ($this->tags as $tag){
            $tags[] = $tag->tag_name;
        }
        return [
            'id' => $this->id,
            'name' => $this->category_name,
            'tag'=> implode(",", $tags),

        ];
    }
}
