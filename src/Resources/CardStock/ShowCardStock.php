<?php

namespace Hanafalah\ModuleItem\Resources\CardStock;

class ShowCardStock extends ViewCardStock
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
            'stock_movement'  => $this->relationValidation('stockMovement', function () {
                return $this->stockMovement->toShowApi();
            }),
            'stock_movements' => $this->relationValidation('stockMovements', function () {
                $stock_movements = $this->stockMovements;
                return $stock_movements->transform(function ($stock) {
                    return $stock->toShowApi();
                });
            }),
            'item' => $this->relationValidation('item', function () {
                return $this->item->toShowApi();
            }),
            'goods_receipt_unit' => $this->relationValidation('goodsReceiptUnit', function () {
                return $this->goodsReceiptUnit->toShowApi();
            }),
            'transaction' => $this->relationValidation('transaction', function () {
                return $this->transaction->toShowApi();
            })
        ];

        $arr = $this->mergeArray(parent::toArray($request), $arr);
        return $arr;
    }
}
