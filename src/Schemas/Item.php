<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\{
    Item as ContractsItem
};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleItem\Contracts\Data\ItemData;
use Illuminate\Support\Str;

class Item extends PackageManagement implements ContractsItem
{
    protected string $__entity = 'Item';
    public $item_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'item',
            'tags'     => ['item', 'item-index'],
            'duration' => 60 * 12
        ]
    ];

    public function prepareStore(ItemData $item_dto): Model{
        if (isset($item_dto->reference)){
            $reference_type   = $item_dto->reference_type;
            $reference_schema = config('module-item.item_reference_types.'.Str::snake($reference_type).'.schema');        
            if (isset($reference_schema)) {
                $schema_reference = $this->schemaContract(Str::studly($reference_schema));
                $reference = $schema_reference->prepareStore($item_dto->reference);
                $item_dto->reference_id = $reference->getKey();
            }   
        }

        $add = [
            'barcode'             => $item_dto->barcode,
            'name'                => $item_dto->name,
            'unit_id'             => $item_dto->unit_id,
            'coa_id'              => $item_dto->coa_id,
            'cogs'                => $item_dto->cogs ?? 0,
            'min_stock'           => $item_dto->min_stock ?? 150,
            'is_using_batch'      => $item_dto->is_using_batch ?? false,
            'net_unit_id'         => $item_dto->net_unit_id,
            'net_qty'             => $item_dto->net_qty,
            'margin'              => $item_dto->margin ?? 0,
            'tax'                 => $item_dto->tax ?? 0,
            'netto'               => $item_dto->netto,
        ];
        if (isset($item_dto->item_code)) $add['item_code'] = $item_dto->item_code;
        if (isset($item_dto->id)) {
            $guard = ['id' => $item_dto->id];
        } else {
            if (isset($item_dto->reference_model)){
                $reference_model = $item_dto->reference_model;
                $item_dto->reference_type = $reference_model->getMorphClass();
                $item_dto->reference_id = $reference_model->getKey();
            }
            $guard = [
                'reference_id'   => $item_dto->reference_id,
                'reference_type' => $item_dto->reference_type
            ];
        }

        $item = $this->ItemModel()->updateOrCreate($guard,$add);
        $item->last_selling_price  = $item_dto->last_selling_price ?? $item->selling_price ?? 0;
        $item->last_cogs           = $item_dto->last_cogs ?? $current_cogs ?? 0;
        if (isset($item_dto->selling_price)) $item->selling_price = $item_dto->selling_price ?? 0;        
        $props = &$item_dto->props->props;
        $props['prop_reference'] = ($item_dto->reference_model ?? $item->reference)->toViewApi()->resolve();

        $item->compositions()->detach();
        if (isset($item_dto->compositions) && count($item_dto->compositions) > 0) {
            $compositions = [];
            $props['prop_compositions'] = [];
            $prop_compositions = &$props['prop_compositions'];
            foreach ($item_dto->compositions as $composition) {
                $compositions[] = $composition = $this->schemaContract('composition')->prepareStoreComposition($composition);
                $prop_compositions[] = $composition->toViewApi()->only(['id','name']);
            }
            
            $item->compositions()->attach($compositions, ['model_type' => $item->getMorphClass()]);
        }

        $props = &$item_dto->props;
        if (isset($props->prop_item_has_variants) && count($props->prop_item_has_variants) > 0) {
            foreach ($props->prop_item_has_variants as &$item_has_variant) {
                $item_has_variant->item_id = $item->getKey();
                $item_has_variant_model = $this->schemaContract('item_has_variant')->prepareStoreItemHasVariant($item_has_variant);
                $item_has_variant = $item_has_variant_model->toViewApi()->resolve();
            }
        }

        $this->fillingProps($item, $item_dto->props);
        $item->save();
        return $item;
    }

    public function prepareStoreItem(ItemData $item_dto): Model{
        $item = $this->prepareStore($item_dto);
        if (isset($item_dto->item_stock)) $this->processItemStock($item_dto,$item);            
        
        $item->compositions()->detach();
        if (isset($item_dto->compositions) && count($item_dto->compositions) > 0) {
            $compositions = [];
            foreach ($item_dto->compositions as $composition) {
                $compositions[] = $this->schemaContract('composition')->prepareStoreComposition($composition);
            }
            $item->compositions()->attach($compositions, ['model_type' => $item->getMorphClass()]);
        }
        $this->fillingProps($item,$item_dto->props);
        $item->save();
        return $this->item_model = $item;
    }

    protected function processItemStock(ItemData &$item_dto, &$item){
        $item_stock_dto = $item_dto->item_stock;
        if (isset($item_stock_dto->warehouse_id) && isset($item_stock_dto->warehouse_type)) {
            if (isset($item_stock_dto->stock) || (isset($item_stock_dto->stock_batches) && count($item_stock_dto->stock_batches) > 0)) {
                if ($item_dto->is_using_batch) {
                    if (!isset($item_stock_dto->stock_batches) || count($item_stock_dto->stock_batches) == 0) {
                        throw new \Exception('No stock batches provided', 422);
                    }
                }
                $funding = $this->FundingModel()->where('props->is_self', true)->first();
                if (!isset($funding)) throw new \Exception('No funding provided', 422);

                $item_stock_schema            = $this->schemaContract('item_stock');
                $item_stock_dto->funding_id   = $funding->getKey();
                $item_stock_dto->subject_type = $item->getMorphClass();
                $item_stock_dto->subject_id   = $item->getKey();
                $item_stock_schema->prepareStoreItemStock($item_stock_dto);
            }
        }
        $item->load('itemStocks');
    }

    public function item(mixed $conditionals = null): Builder{
        return $this->generalSchemaModel();
    }
}
