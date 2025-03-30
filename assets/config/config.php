<?php

use Hanafalah\ModuleItem\{
    Models as ModuleItem,
    Contracts
};

return [
    'app' => [
        'contracts' => [
        ],
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts',
        'schema' => 'Schemas'
    ],
    'database'  => [
        'models' => [
        ]
    ],
    'warehouse' => null, //please enter your warehouse model
    'update_price_from_procurement' => [
        'enable' => true,
        'method' => 'AVERAGE'
    ]
];
