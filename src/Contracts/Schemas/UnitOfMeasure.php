<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\UnitOfMeasureData;
//use Hanafalah\ModuleItem\Contracts\Data\UnitOfMeasureUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\UnitOfMeasure
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateUnitOfMeasure(?UnitOfMeasureData $unit_of_measure_dto = null)
 * @method Model prepareUpdateUnitOfMeasure(UnitOfMeasureData $unit_of_measure_dto)
 * @method bool deleteUnitOfMeasure()
 * @method bool prepareDeleteUnitOfMeasure(? array $attributes = null)
 * @method mixed getUnitOfMeasure()
 * @method ?Model prepareShowUnitOfMeasure(?Model $model = null, ?array $attributes = null)
 * @method array showUnitOfMeasure(?Model $model = null)
 * @method Collection prepareViewUnitOfMeasureList()
 * @method array viewUnitOfMeasureList()
 * @method LengthAwarePaginator prepareViewUnitOfMeasurePaginate(PaginateData $paginate_dto)
 * @method array viewUnitOfMeasurePaginate(?PaginateData $paginate_dto = null)
 * @method array storeUnitOfMeasure(?UnitOfMeasureData $unit_of_measure_dto = null)
 * @method Collection prepareStoreMultipleUnitOfMeasure(array $datas)
 * @method array storeMultipleUnitOfMeasure(array $datas)
 */

interface UnitOfMeasure extends DataManagement
{
    public function prepareStoreUnitOfMeasure(UnitOfMeasureData $unit_of_measure_dto): Model;
}