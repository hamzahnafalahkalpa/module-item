<?php

namespace Gii\ModuleItem\Resources\Item;

use Illuminate\Http\Request;
use Zahzah\LaravelSupport\Resources\ApiResource;

class ViewItem extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request) : array{
      $arr = [
        'id'                 => $this->id,
        'name'               => $this->name,
        'barcode'            => $this->barcode,
        'item_code'          => $this->item_code,
        'margin'            => $this->margin,
        'is_using_batch'     => $this->is_using_batch == 1 ? true : false,
        'is_has_funding'     => $this->itemStock()->whereNotNull('funding_id')->first() ? true : false,
        'is_has_composition' => $this->is_has_composition ?? false,
        'composition_total'  => $this->composition_total ?? 0,
        'compositions'       => $this->relationValidation('compositions',function(){
          return $this->compositions->transform(function($composition){
            return $composition->toViewApi();
          });
        }),
        'unit'               => [
          'unit_id'   => $this->unit_id ?? null,
          'unit_name' => $this->unit_name ?? null
        ],
        'item_stock'  => $this->relationValidation('itemStock',function(){
            $itemStock = $this->itemStock;
            return $itemStock->toShowApi();
        }),
        'item_stocks'  => $this->relationValidation('itemStocks',function(){
            $itemStocks = $this->itemStocks;
            return $itemStocks->transform(function($item_stock){
              return $item_stock->toShowApi();
            });
        }),
        'selling_price'    => $this->selling_price,
        'cogs'             => $this->cogs,
        'status'           => $this->status,
        'stock'            => $this->stock,
        'min_stock'        => $this->min_stock,
        'reference_type'   => $this->reference_type,
        'created_at'       => $this->created_at,
        'updated_at'       => $this->updated_at
      ];
      $props = $this->getPropsData();
      foreach ($props as $key => $prop) {
          $arr[$key] = $prop;
      }

      return $arr;
  }
}
