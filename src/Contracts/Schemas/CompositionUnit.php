<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\CompositionUnitData;
//use Hanafalah\ModuleItem\Contracts\Data\CompositionUnitUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\CompositionUnit
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateCompositionUnit(?CompositionUnitData $composition_unit_dto = null)
 * @method Model prepareUpdateCompositionUnit(CompositionUnitData $composition_unit_dto)
 * @method bool deleteCompositionUnit()
 * @method bool prepareDeleteCompositionUnit(? array $attributes = null)
 * @method mixed getCompositionUnit()
 * @method ?Model prepareShowCompositionUnit(?Model $model = null, ?array $attributes = null)
 * @method array showCompositionUnit(?Model $model = null)
 * @method Collection prepareViewCompositionUnitList()
 * @method array viewCompositionUnitList()
 * @method LengthAwarePaginator prepareViewCompositionUnitPaginate(PaginateData $paginate_dto)
 * @method array viewCompositionUnitPaginate(?PaginateData $paginate_dto = null)
 * @method Model prepareStoreCompositionUnit(CompositionUnitData $composition_unit_dto)
 * @method array storeCompositionUnit(?CompositionUnitData $composition_unit_dto = null)
 * @method Collection prepareStoreMultipleCompositionUnit(array $datas)
 * @method array storeMultipleCompositionUnit(array $datas)
 */

interface CompositionUnit extends ItemStuff{}