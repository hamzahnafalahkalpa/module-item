<?php

use Hanafalah\ModuleItem\{
    Models as ModuleItem,
    Contracts
};

return [
    'app' => [
        'contracts' => [
            'card_stock'    => Contracts\CardStock::class,
            'composition'   => Contracts\Composition::class,
            'item'          => Contracts\Item::class,
            'item_stock'    => Contracts\ItemStock::class,
            'item_stuff'    => Contracts\ItemStuff::class
        ],
    ],
    'database'  => [
        'models' => [
            'Item'                => ModuleItem\Item::class,
            'ItemStuff'           => ModuleItem\ItemStuff::class,
            'BillOfMaterial'      => ModuleItem\BillOfMaterial::class,
            'Material'            => ModuleItem\Material::class,
            'ItemStock'           => ModuleItem\ItemStock::class,
            'CardStock'           => ModuleItem\CardStock::class,
            'Composition'         => ModuleItem\Composition::class,
            'ModelHasComposition' => ModuleItem\ModelHasComposition::class
        ]
    ],
    'warehouse' => null, //please enter your warehouse model
    'update_price_from_procurement' => [
        'enable' => true,
        'method' => 'AVERAGE'
    ]
];
