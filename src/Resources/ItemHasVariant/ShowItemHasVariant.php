<?php

namespace Hanafalah\ModuleItem\Resources\ItemHasVariant;

use Illuminate\Http\Request;

class ShowItemHasVariant extends ViewItemHasVariant
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
        ];

        $arr = $this->mergeArray(parent::toArray($request), $arr);
        return $arr;
    }
}
