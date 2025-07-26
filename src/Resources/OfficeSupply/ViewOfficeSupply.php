<?php

namespace Hanafalah\ModuleItem\Resources\OfficeSupply;

use Hanafalah\ModuleItem\Resources\InventoryItem\ViewInventoryItem;

class ViewOfficeSupply extends ViewInventoryItem
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray(\Illuminate\Http\Request $request): array
  {

    $arr = [];
    $arr = $this->mergeArray(parent::toArray($request), $arr);
    return $arr;
  }
}
