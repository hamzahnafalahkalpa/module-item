<?php

namespace Hanafalah\ModuleItem\Resources\StuffSupply;

use Hanafalah\ModuleItem\Resources\InventoryItem\ShowInventoryItem;

class ShowStuffSupply extends ViewStuffSupply
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
    $show = $this->resolveNow(new ShowInventoryItem($this));
    $arr = $this->mergeArray(parent::toArray($request),$show,$arr);
    return $arr;
  }
}
