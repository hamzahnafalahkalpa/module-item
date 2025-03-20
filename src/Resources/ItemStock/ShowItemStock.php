<?php

namespace Gii\ModuleItem\Resources\ItemStock;

use Illuminate\Http\Request;

class ShowItemStock extends ViewItemStock
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request) : array{
      $arr = [
        
      ];
      $arr = $this->mergeArray(parent::toArray($request),$arr);
      
      return $arr;
  }
}