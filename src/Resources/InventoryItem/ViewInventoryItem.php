<?php

namespace Hanafalah\ModuleItem\Resources\InventoryItem;

use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewInventoryItem extends ApiResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray(\Illuminate\Http\Request $request): array
  {
    $arr = [
      'id'    => $this->id,
      'name'  => $this->name,
      'flag'  => $this->flag,
      'label' => $this->label,
    ];
    $arr = $this->mergeArray(parent::toArray($request), $arr);
    return $arr;
  }
}
