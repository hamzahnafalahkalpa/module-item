<?php

namespace Gii\ModuleItem\Resources\Material;

use Illuminate\Http\Request;
use Zahzah\LaravelSupport\Resources\ApiResource;

class ViewMaterial extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request) : array{
      $arr = [
        'id'               => $this->id,
        'name'             => $this->name,
        'created_at'       => $this->created_at,
        'updated_at'       => $this->updated_at
      ];
      
      return $arr;
  }
}