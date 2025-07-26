<?php

namespace Hanafalah\ModuleItem\Resources\ItemStuff;

use Illuminate\Http\Request;
use Hanafalah\LaravelSupport\Resources\Unicode\ShowUnicode;

class ShowItemStuff extends ViewItemStuff
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
    $show = $this->resolveNow(new ShowUnicode($this));
    $arr = $this->mergeArray(parent::toArray($request), $show, $arr);
    return $arr;
  }
}
