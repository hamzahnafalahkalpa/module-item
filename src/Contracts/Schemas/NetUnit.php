<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\NetUnitData;
//use Hanafalah\ModuleItem\Contracts\Data\NetUnitUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\NetUnit
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateNetUnit(?NetUnitData $net_unit_dto = null)
 * @method Model prepareUpdateNetUnit(NetUnitData $net_unit_dto)
 * @method bool deleteNetUnit()
 * @method bool prepareDeleteNetUnit(? array $attributes = null)
 * @method mixed getNetUnit()
 * @method ?Model prepareShowNetUnit(?Model $model = null, ?array $attributes = null)
 * @method array showNetUnit(?Model $model = null)
 * @method Collection prepareViewNetUnitList()
 * @method array viewNetUnitList()
 * @method LengthAwarePaginator prepareViewNetUnitPaginate(PaginateData $paginate_dto)
 * @method array viewNetUnitPaginate(?PaginateData $paginate_dto = null)
 * @method array storeNetUnit(?NetUnitData $net_unit_dto = null)
 * @method Collection prepareStoreMultipleNetUnit(array $datas)
 * @method array storeMultipleNetUnit(array $datas)
 */

interface NetUnit extends ItemStuff{
    public function prepareStoreNetUnit(NetUnitData $net_unit_dto): Model;
    public function netUnit(mixed $conditionals = null): Builder;
}