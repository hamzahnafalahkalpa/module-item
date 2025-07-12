<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\CardStock\{
    ShowCardStock,
    ViewCardStock
};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\ModuleWarehouse\Enums\MainMovement\Direction;
use Hanafalah\ModuleWarehouse\Enums\MainMovement\PriceUpdateMethod;

class CardStock extends BaseModel
{
    use HasUlids, HasProps, SoftDeletes;

    public $incrementing  = false;
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    protected $list       = [
        'id', 'parent_id', 'reference_type', 'reference_id', 
        'item_id', 'transaction_id', 'reported_at', 
        'request_qty', 'total_qty', 'total_tax', 'total_cogs'
    ];
    protected $show       = [];
    protected $casts      = [
        'name'        => 'string',
        'reported_at' => 'date'
    ];

    protected static function booted(): void{
        parent::booted();
        static::created(function ($query) {
            $transactionItem = $query->transactionItem;
            if (!isset($transactionItem)) {
                $item = $query->item;

                $transactionItem = $query->transactionItem()->firstOrCreate([
                    'transaction_id' => $query->transaction_id,
                    'item_type'      => $item->reference_type,
                    'item_id'        => $item->reference_id,
                    'item_name'      => $item->name
                ]);
            }
        });
        static::updating(function ($query) {
            if ($query->isDirty('reported_at')) {
                $query->load([
                    'stockMovements' => function ($query) {
                        $query->withoutGlobalScope('parent_only')
                            ->whereHas('itemStock', function ($query) {
                                $query->whereNotNull('funding_id');
                            });
                    }
                ]);
                $stock_movements = $query->stockMovements;
                $card_stock      = $query;
                $item            = $query->item;
                foreach ($stock_movements as $stock_movement) {
                    $item               = $card_stock->item;
                    $item_stock         = $stock_movement->itemStock;
                    $main_stock         = $item_stock->parent;
                    $current_stock      = $item_stock->stock ?? 0;
                    $goods_receipt_unit = $stock_movement->goodsReceiptUnit;
                    $multiple           = isset($goods_receipt_unit) ? $goods_receipt_unit->unit_qty : 1;
                    if (isset($item_stock)) {
                        //ADD NON FUNDING STOCK TO MOVEMENT IF NOT AVAILABLE
                        $stock_movement->load([
                            'parent' => function ($query) use ($stock_movement) {
                                $query->where('reference_id', $stock_movement->reference_id)
                                    ->where('reference_type', $stock_movement->reference_type)
                                    ->where('direction', $stock_movement->direction);
                            }
                        ]);
                        $batch_movements = $stock_movement->batchMovements;
                        $is_has_batch    = isset($batch_movements) && count($batch_movements) > 0;

                        $stock_movement->backup_parent_id = $stock_movement->parent_id ?? null;
                        if (isset($item_stock->funding_id) && !isset($stock_movement->parent)) {
                            static::createParentMovement($stock_movement, $item_stock);
                            $stock_movement->refresh();
                            $stock_movement->receipt_qty = $stock_movement->qty;
                            if (!$is_has_batch) $stock_movement->qty *= $multiple;
                        } else {
                            $stock_movement->refresh();
                        }

                        //UPDATING BATCH
                        if ($is_has_batch) {
                            foreach ($batch_movements as $batch_movement) {
                                $stock_batch = $batch_movement->stockBatch;
                                //ADD NON FUNDING STOCK TO MOVEMENT IF NOT AVAILABLE
                                if (isset($item_stock->funding_id) && !isset($batch_movement->parent_id)) {
                                    static::createParentMovement($batch_movement, $stock_batch);
                                    $batch_movement->refresh();
                                    $batch_movement->receipt_qty = $batch_movement->qty;
                                    $batch_movement->qty *= $multiple;
                                }
                                $parent_batch_movement         = $batch_movement->parent;
                                $parent_stock_batch            = $stock_batch->parent;
                                $parent_receipt_qty            = $parent_batch_movement->qty;
                                $batch_movement->opening_stock = $stock_batch->stock;
                                static::calculatingStock($batch_movement, $stock_batch, $stock_movement->direction);
                                $batch_movement->refresh();
                                $parent_batch_movement->refresh();
                                if (isset($parent_batch_movement) && isset($parent_stock_batch)) {
                                    $parent_batch_movement->receipt_qty   = $parent_receipt_qty;
                                    $parent_batch_movement->opening_stock = $parent_stock_batch->stock;
                                    static::calculatingStock($parent_batch_movement, null, $stock_movement->direction);
                                }
                                $parent_batch_movement->refresh();
                                $parent_stock_batch->refresh();
                            }
                        }
                        $stock_movement->refresh();
                        $item_stock->refresh();
                        $stock_movement->opening_stock = $current_stock;
                        list($stock_movement, $item_stock) = static::calculatingStock($stock_movement, $item_stock, $stock_movement->direction);
                        $stock_movement->refresh();
                        $item_stock->refresh();
                        $parent_stock_movement = $stock_movement->parent;
                        $parent_item_stock     = $item_stock->parent;
                        if (isset($parent_stock_movement) && isset($parent_item_stock)) {
                            $parent_stock_movement->opening_stock = $main_stock->stock;
                            static::calculatingStock($parent_stock_movement, null, $stock_movement->direction);
                        }
                    }

                    //UPDATING PRICE FOR PROCUREMENT CONDITION
                    if (config('module-item.update_price_from_procurement.enable', false)) {
                        if ($stock_movement->direction == Direction::IN->value) {
                            $method = config('module-item.update_price_from_procurement.method');
                            if (isset($method) && isset($item) && $card_stock && $card_stock->is_procurement) {
                                $qty       = $stock_movement->qty * $multiple;
                                $cogs      = $stock_movement->total_cogs / $stock_movement->qty;
                                $qty_total = $current_stock + $qty;

                                $margin = intval($card_stock->margin ?? $stock_movement->margin ?? 0);

                                switch ($method) {
                                    case PriceUpdateMethod::AVERAGE->value:
                                        $new_cogs = ($qty_total > 0)
                                            ? ($current_stock * $item->cogs + $stock_movement->total_cogs) / $qty_total
                                            : $item->cogs;
                                    break;
                                    case PriceUpdateMethod::MIN->value: $new_cogs = ($item->cogs > $cogs) ? $item->cogs : $cogs;break;
                                    case PriceUpdateMethod::MAX->value: $new_cogs = ($item->cogs < $cogs) ? $cogs : $item->cogs;break;
                                }
                                $stock_movement->new_cogs          = $new_cogs;
                                $stock_movement->new_selling_price = $new_cogs;
                                if ($margin != 0) $stock_movement->new_selling_price += $new_cogs * $margin / 100;

                                $item->cogs          = $new_cogs;
                                $item->selling_price = $stock_movement->new_selling_price;
                                $item->margin        = $stock_movement->margin ?? $item->margin;
                                $item->save();
                                $stock_movement->save();
                            }
                        }
                    }
                }
            }
        });
    }

    private static function calculatingStock($movement_model, $stock_model = null, $direction){
        $opening_stock = $movement_model->opening_stock;
        switch ($direction) {
            case Direction::IN->value     : $closing = $opening_stock + $movement_model->qty;break;
            case Direction::OUT->value    : $closing = $opening_stock - $movement_model->qty;break;
            case Direction::OPNAME->value : $closing = $movement_model->qty;break;
        }
        $movement_model->closing_stock = $closing;
        $movement_model->save(); 
        if (isset($stock_model)) {
            $stock_model->stock = $closing;
            $stock_model->save();
        }
        return [$movement_model, $stock_model];
    }

    protected static function createParentMovement($movement_model, $stock_model){
        $stock_parent_model = $stock_model->parent;
        if (!isset($stock_parent_model)) throw new \Exception('Parent stock not found on card stock event processing', 422);
        if (isset($movement_model->card_stock_id)) { //IS STOCK MOVEMENT IDENTIFIED BY card_stock_id
            $guard = [
                'item_stock_id'          => $stock_parent_model->getKey(),
                'goods_receipt_unit_id'  => $movement_model->goods_receipt_unit_id ?? null
            ];
            $default_guard = ['direction', 'card_stock_id', 'reference_type', 'reference_id'];
        } else {
            $stock_movement = $movement_model->stockMovement;
            $guard         = [
                'stock_batch_id'    => $stock_parent_model->getKey(),
                'stock_movement_id' => $stock_movement->parent_id
            ];
            $default_guard = ['batch_id'];
        }
        foreach ($default_guard as $key) $guard[$key] = $movement_model->{$key};
        $parent_movement_model = $movement_model->firstOrCreate($guard, [
            'parent_id'     => $movement_model->backup_parent_id ?? null,
            'qty'           => 0,
            // 'opening_stock' => $stock_parent_model->stock, 
            // 'closing_stock' => $stock_parent_model->stock
        ]);
        if (isset($movement_model->backup_parent_id)) unset($movement_model->backup_parent_id);

        $movement_model->parent_id = $parent_movement_model->getKey();
        $movement_model->save();
        // $parent_movement_model->qty           += $movement_model->qty;
        // $parent_movement_model->closing_stock += $movement_model->qty;
        // $movement_model->qty      *= $multiple;
        // static::withoutEvents(function () use ($movement_model) {
        // });
        // $parent_movement_model->refresh();
        return $parent_movement_model;
    }

    public function viewUsingRelation(): array{
        return [
        ];
    }

    public function showUsingRelation(): array{
        return [
            'goodsReceiptUnit', 
            'stockMovements' => function ($query) {
                $query->with([
                    'reference',
                    'batchMovements',
                    'itemStock'
                ]);
            }
        ];
    }

    public function getShowResource(){
        return ShowCardStock::class;
    }

    public function getViewResource(){
        return ViewCardStock::class;
    }

    public function reference(){return $this->morphTo();}
    public function item(){return $this->belongsToModel('Item');}
    public function goodsReceiptUnit(){return $this->hasOneModel('GoodsReceiptUnit');}
    public function goodsReceiptUnits(){return $this->hasManyModel('GoodsReceiptUnit');}
    public function stockMovement(){
        return $this->hasOneModel('StockMovement');
    }
    public function stockMovements(){return $this->hasManyModel('StockMovement');}
    public function transaction(){return $this->belongsToModel('Transaction');}

    public function transactionItem(){
        $transactionItemTable = $this->TransactionItemModel()->getTable();
        return $this->hasOneThroughModel(
            'TransactionItem',
            'Item',
            'id',
            'item_id',
            'item_id',
            'reference_id'
        )->whereRaw($transactionItemTable . ".item_type = ".$this->ItemModel()->getTable() .".reference_type AND " . $transactionItemTable . ".transaction_id = '" . $this->transaction_id . "'");
    }
}
