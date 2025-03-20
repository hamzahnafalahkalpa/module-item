<?php

namespace Hanafalah\ModuleItem\Resources\Composition;

use Illuminate\Http\Request;
use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewComposition extends ApiResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray(Request $request): array
  {
    $arr = [
      'id'               => $this->id,
      'name'             => $this->name,
      'unit_scale'       => $this->unit_scale,
      'unit_id'          => $this->unit_id,
      'unit_name'        => $this->unit_name
    ];

    return $arr;
  }
}
