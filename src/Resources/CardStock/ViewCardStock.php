<?php

namespace Gii\ModuleItem\Resources\CardStock;

use Zahzah\LaravelSupport\Resources\ApiResource;

class ViewCardStock extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(\Illuminate\Http\Request $request) : array{
        $arr = [
            'id'        => $this->id,
            'item_type' => $this->item_type,
            'item_id'   => $this->item_id,
            'stock_movement'  => $this->relationValidation('stockMovement',function(){
                return $this->stockMovement->toViewApi();
            }),
            'transaction' => $this->relationValidation('transaction',function(){
                return $this->transaction->toViewApi();
            }),
            'created_at'  => $this->created_at,
            'reported_at' => $this->reported_at,
            'props'       => $this->getPropsData() ?? null
        ];
        
        return $arr;
  }
}