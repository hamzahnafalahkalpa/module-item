<?php

namespace Gii\ModuleItem\Resources\ItemStuff;

use Illuminate\Http\Request;
use Zahzah\LaravelSupport\Resources\ApiResource;

class ViewItemStuff extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request) : array{
      $arr = [
        'id'     => $this->id,
        'name'   => $this->name,
        'childs' => $this->relationValidation('childs',function(){
          $childs = $this->childs;
          return $childs->transform(function($child){
            return $child->toViewApi();
          });
        })
      ];
      
      return $arr;
  }
}