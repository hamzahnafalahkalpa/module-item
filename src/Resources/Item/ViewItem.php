<?php

namespace Hanafalah\ModuleItem\Resources\Item;

use Illuminate\Http\Request;
use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewItem extends ApiResource
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
        'id'                 => $this->id,
        'name'               => $this->name,
        'barcode'            => $this->barcode,
        'item_code'          => $this->item_code,
        'reference_type'     => $this->reference_type,
        'reference'          => $this->relationValidation('reference',function(){
            return $this->reference->toViewApi()->resolve();
        },$this->prop_reference),
        'margin'             => $this->margin,
        'is_using_batch'     => $this->is_using_batch == 1 ? true : false,
        'is_has_funding'     => $this->itemStock()->whereNotNull('funding_id')->first() ? true : false,
        'compositions'       => $this->prop_compositions,
        'unit_id'            => $this->unit_id,
        'unit'               => $this->prop_unit,
        'item_stock'         => $this->relationValidation('itemStock', function () {
            return $this->itemStock->toViewApi()->resolve();
        }),
        'item_stocks'  => $this->relationValidation('itemStocks', function () {
            return $this->itemStocks->transform(function ($item_stock) {
                return $item_stock->toViewApi()->resolve();
            });
        }),
        'card_stock' => $this->relationValidation('cardStock', function () {
            return $this->cardStock->toViewApi()->resolve();
        }),
        'item_has_variants' => $this->relationValidation('itemHasVariants', function () {
            return $this->itemHasVariants->transform(function ($item_has_variant) {
                return $item_has_variant->toViewApi();
            });
        },$this->prop_item_has_variants),
        'selling_price'    => $this->selling_price,
        'cogs'             => $this->cogs,
        'status'           => $this->status,
        'min_stock'        => $this->min_stock,
        'created_at'       => $this->created_at,
        'updated_at'       => $this->updated_at
    ];

    return $arr;
  }
}
