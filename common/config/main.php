<?php
return [
    'name' => 'Online Lib',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'replaceBookQueue' => [
            'class' => 'common\components\RabbitMQ',
        ]
    ],
];
