<?php

namespace Hanafalah\ModuleItem\Resources\SellingForm;

use Hanafalah\ModuleItem\Resources\ItemStuff\ShowItemStuff;

class ShowSellingForm extends ViewSellingForm
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
    $show = $this->resolveNow(new ShowItemStuff($this));
    $arr = $this->mergeArray(parent::toArray($request),$show,$arr);
    return $arr;
  }
}
