<?php

namespace Hanafalah\ModuleItem\Resources\Inventory;

use Hanafalah\LaravelSupport\Resources\ApiResource;

use Illuminate\Support\Str;

class ViewInventory extends ApiResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray(\Illuminate\Http\Request $request): array
  {
    $arr = [
      'id'                 => $this->id,
      'inventory_code'     => $this->inventory_code,
      'name'               => $this->name,
      'reference_type'     => $this->reference_type,
      'reference_type'     => $this->reference_type,
      'reference'          => $this->{'prop_'.Str::snake($this->reference_type)},
      'item'               => $this->prop_item,
      'brand_id'           => $this->brand_id,
      'brand'              => $this->prop_brand,
      'supply_category_id' => $this->supply_category_id,
      'supply_category'    => $this->prop_supply_category,
      'model_name'         => $this->model_name
    ];
    return $arr;
  }
}
