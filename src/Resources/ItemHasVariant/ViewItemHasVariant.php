<?php

namespace Hanafalah\ModuleItem\Resources\ItemHasVariant;

use Illuminate\Http\Request;
use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewItemHasVariant extends ApiResource
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
      'id'             => $this->id,
      'item_id'        => $this->item_id,
      'variant_name'   => $this->variant_name,
      'variant_label'  => $this->variant_label,
      'variant_type'   => $this->variant_type,
      'variant_id'     => $this->variant_id,
      'variant'        => $this->prop_variant
    ];
    return $arr;
  }
}
