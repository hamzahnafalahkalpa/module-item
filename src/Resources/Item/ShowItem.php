<?php

namespace Hanafalah\ModuleItem\Resources\Item;

use Hanafalah\ModuleItem\Resources\Material\ShowMaterial;
use Hanafalah\ModuleItem\Resources\ItemStock\ShowItemStock;
use Illuminate\Http\Request;

class ShowItem extends ViewItem
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
            'tax'                => $this->tax,
            'margin'             => $this->margin,
            'last_cogs'          => $this->last_cogs,
            'last_selling_price' => $this->last_selling_price,
            'netto'              => $this->netto,
            'net_qty'            => $this->net_qty,
            'net_unit'           => $this->prop_net_unit,
            'reference' => $this->relationValidation('reference', function () {
                $reference = $this->reference;
                return $reference->toShowApi();
            }),
            'materials' => $this->relationValidation('materials', function () {
                $materials = $this->materials;
                return $materials->transform(function ($material) {
                    return $material->toShowApi();
                });
            }),
            'compositions' => $this->relationValidation('compositions', function () {
                return $this->compositions->transform(function ($composition) {
                    return $composition->toViewApi();
                });
            }),
            'item_stocks' => $this->relationValidation('itemStocks', function () {
                $itemStocks = $this->itemStocks;
                return $itemStocks->transform(function ($itemStock) {
                    return $itemStock->toShowApi();
                });
            }),
            'card_stock' => $this->relationValidation('cardStock', function () {
                return $this->cardStock->toShowApi();
            })
        ];

        $arr = $this->mergeArray(parent::toArray($request), $arr);

        return $arr;
    }
}
