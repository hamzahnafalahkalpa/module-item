<?php

namespace Hanafalah\ModuleItem\Resources\ItemStock;

use Illuminate\Http\Request;
use Hanafalah\ModuleWarehouse\Resources\Stock\ViewStock;

class ViewItemStock extends ViewStock
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray(Request $request): array
  {
    $arr = [];
    $arr = $this->mergeArray(parent::toArray($request), $arr);
    return $arr;
  }
}
