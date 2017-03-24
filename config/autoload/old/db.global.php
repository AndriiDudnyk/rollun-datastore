<?php
use Zend\Db\Adapter\AdapterAbstractServiceFactory;

return [
    'dependencies' => [
        'abstract_factories' => [
            AdapterAbstractServiceFactory::class,
        ],
    ],
    'db' => [
        'adapters' => [
            'db' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'zaboy_rest',
                'username' => 'uzaboy_rest',
                'password' => '123321qweewq'
            ]
        ]
    ]
];
