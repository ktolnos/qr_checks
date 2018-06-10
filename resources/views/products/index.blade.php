@extends('common.view', [
    'singular' => 'product',
    'plural' => 'products',
    'urlBase' => 'products',
    'titles' => [
        'Name',
        'Price',
        'Quantity',
        'Sum',
    ],
    'buttonsColumn' => true,

    'items' => $products,
    'rowTemplate' => 'common/view_row',
    'rowParams' => [
            'props' => [
            'name',
            'price',
            'quantity',
            'sum'
        ],
        'idProp' => 'id',
        'buttons' => [
            'edit',
            'delete',
            'view'
        ]
    ]
])

