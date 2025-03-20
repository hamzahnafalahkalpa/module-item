<?php

namespace Hanafalah\ModuleItem\Resources\Material;

use Hanafalah\ModuleItem\Resources\Item\ViewItem;
use Illuminate\Http\Request;

class ShowMaterial extends ViewMaterial
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
      'items' => $this->relationValidation('items', function () {
        $items = $this->items;
        return $items->transform(function ($item) {
          return new ViewItem($item);
        });
      })
    ];

    $arr = $this->mergeArray(parent::toArray($request), $arr);

    return $arr;
  }
}
