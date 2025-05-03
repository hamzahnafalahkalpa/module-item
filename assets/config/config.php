<?php

use Hanafalah\ModuleItem\{
    Models as ModuleItem,
    Contracts
};

return [
    'namespace' => 'Hanafalah\\ModuleItem',
    'app' => [
        'contracts' => [
            //ADD YOUR CONTRACTS HERE
        ]
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts',
        'schema' => 'Schemas',
        'database' => 'Database',
        'data' => 'Data',
        'resource' => 'Resources',
        'migration' => '../assets/database/migrations'
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
