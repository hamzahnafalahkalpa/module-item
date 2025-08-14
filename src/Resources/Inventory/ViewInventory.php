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
      Str::snake($this->reference_type) => $this->prop_reference,
      'item'               => $this->propNil($this->prop_item,'reference'),
      'brand_id'           => $this->brand_id,
      'brand'              => $this->propOnlies($this->prop_brand, 'id', 'name', 'flag', 'label'),
      'supply_category_id' => $this->supply_category_id,
      'supply_category'    => $this->propOnlies($this->prop_supply_category, 'id', 'name', 'flag', 'label'),
      'model_name'         => $this->model_name
    ];
    return $arr;
  }
}
