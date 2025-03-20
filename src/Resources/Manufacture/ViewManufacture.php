<?php

namespace Gii\ModuleItem\Resources\Manufacture;

use Illuminate\Http\Request;
use Zahzah\LaravelSupport\Resources\ApiResource;

class ViewManufacture extends ApiResource
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
        'name'             => $this->name
      ];
      
      return $arr;
  }
}