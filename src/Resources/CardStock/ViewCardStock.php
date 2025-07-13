<?php

namespace Hanafalah\ModuleItem\Resources\CardStock;

use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewCardStock extends ApiResource
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
            'id'              => $this->id,
            'item_id'         => $this->item_id,
            'item'            => $this->prop_item,
            'reference'       => $this->prop_reference,
            'stock_movement'  => $this->relationValidation('stockMovement', function () {
                return $this->stockMovement->toViewApi()->resolve();
            }),
            'transaction'       => $this->prop_transaction,
            'warehouse'         => $this->prop_warehouse,
            'tax'               => $this->tax ?? null,
            'qty'               => floatval($this->qty),
            'cogs'              => $this->cogs,
            'price'             => $this->price,
            'receive_qty'       => isset($this->receive_qty) ? floatval($this->receive_qty) : null,
            'request_qty'       => isset($this->request_qty) ? floatval($this->request_qty) : null,
            'total_qty'         => isset($this->total_qty) ? floatval($this->total_qty) : null,
            'total_tax'         => isset($this->total_tax) ? intval($this->total_tax) : null,
            'total_cogs'        => isset($this->total_cogs) ? intval($this->total_cogs) : null,
            'is_procurement'    => $this->is_procurement ?? false,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'reported_at'       => $this->reported_at
        ];

        return $arr;
    }
}
