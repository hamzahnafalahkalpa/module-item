<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\CompositionData as DataCompositionData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Contracts\BaseData;

class CompositionData extends Data implements DataCompositionData, BaseData{
    public function __construct(
        #[MapInputName('id')]
        #[MapName('id')]
        public mixed $id,

        #[MapInputName('name')]
        #[MapName('name')]
        public string $name,

        #[MapInputName('unit_scale')]
        #[MapName('unit_scale')]
        public string $unit_scale,

        #[MapInputName('unit_id')]
        #[MapName('unit_id')]
        public mixed $unit_id
    ){}
}