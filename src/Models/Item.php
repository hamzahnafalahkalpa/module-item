<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Concerns\HasComposition;
use Hanafalah\ModuleItem\Resources\Item\ShowItem;
use Hanafalah\ModuleItem\Resources\Item\ViewItem;
use Hanafalah\ModuleService\Concerns\HasServicePrice;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;

class Item extends BaseModel
{
    use HasProps, SoftDeletes, HasComposition, HasServicePrice;

    protected $list     = [
        'id','barcode','item_code','reference_type','reference_id',
        'name','unit_id','selling_price','coa_id','cogs','min_stock','is_using_batch',
        'status','props'
    ];

    protected $show     = [
        'last_selling_price','last_cogs','margin','tax','netto','net_qty',
        'net_unit_id'
    ];

    protected $casts = [
        'name' => 'string',
        'selling_price' => 'int'
    ];

    public function viewUsingRelation(): array{
        return [];
    }

    public function showUsingRelation(): array{
        return [
            'reference', 'itemStock' => function ($query) {
                $query->whereNull('funding_id')->with([
                    'stockBatches.batch',
                    'childs.stockBatches.batch'
                ]);
            }
        ];
    }

    public function getViewResource(){
        return ViewItem::class;
    }

    public function getShowResource(){
        return ShowItem::class;
    }

    protected static function booted(): void{
        parent::booted();
        static::creating(function ($query) {
            if (!isset($query->transaction_code)) {
                $query->transaction_code = static::hasEncoding('ITEM');
            }
            if (!isset($query->cogs))               $query->cogs = 0;
            if (!isset($query->last_cogs))          $query->last_cogs = 0;
            if (!isset($query->last_selling_price)) $query->last_selling_price = 0;
            if (!isset($query->selling_price))      $query->selling_price = 0;
        });
        static::created(function ($query) {
            if (isset($query->margin) && isset($query->cogs)) {
                if (!$query->isDirty(['selling_price'])) $query->selling_price = static::updateSellingPrice($query);
            }
            static::withoutEvents(function () use ($query) {
                $query->save();
            });
        });
        static::updating(function ($query) {
            if ($query->isDirty(['margin', 'cogs'])) {
                $query->last_cogs          = $query->getOriginal('cogs');
                $query->last_selling_price = $query->getOriginal('selling_price') ?? 0;
                if (!$query->isDirty(['selling_price'])) {
                    $query->selling_price = static::updateSellingPrice($query);
                }
            }
        });
    }

    private static function updateSellingPrice($query){
        $selling_price = ($query->isDirty('selling_price')) ? $query->selling_price : round(((($query->margin ?? 0) / 100) * $query->cogs) + $query->cogs);
        $query->servicePrice()->create([
            'price' => $selling_price
        ]);
        return $selling_price;
    }

    public function scopeUsingBatch($builder){return $builder->where('is_using_batch', true);}
    public function unit(){return $this->belongsToModel('ItemStuff', 'unit_id');}
    public function reference(){return $this->morphTo();}
    public function netUnit(){return $this->belongsToModel('ItemStuff', 'net_unit_id');}
    public function itemStock(){return $this->morphOneModel('ItemStock', 'subject');}
    public function itemStocks(){return $this->morphManyModel('ItemStock', 'subject');}
    public function cardStock(){return $this->hasOneModel('CardStock');}
    public function cardStocks(){return $this->hasManyModel('CardStock');}
    
    public function manufacture(){
        return $this->belongsToManyModel(
            'Manufacture',
            'ModelHasManufacture',
            'model_id',
            $this->ManufactureModel()->getForeignKey()
        )->where('model_type', $this->getMorphClass());
    }
    
    public function materials(){
        return $this->belongsToManyModel(
            'Material',
            'BillOfMaterial',
            'item_id',
            'material_id'
        );
    }

    public function coa(){return $this->belongsToModel('Coa');}
}
