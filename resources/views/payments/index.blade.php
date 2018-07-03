@extends('common.view', [
    'items' => $payments,
    'singular' => 'payment',
    'plural' => 'payments',
    'urlBase' => 'payments',
    'columns' => [
             [
                    'title' => 'ID',
                    'data' => 'id',
                    'width' => '5',
            ],
            [
                    'title' => 'Payer',
                    'data' => 'payer_name',
                    'width' => '33',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Date added',
                    'data' => 'created_at',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Payee',
                    'data' => 'payee_name',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Check',
                    'data' => 'check_id',
                    'sortable' => true,
                    'groupable' => true,
                    'template' => '<a :href="\'products?check_ids=\'+cell.row[\'check_id\']">{{cell.row[\'check_date\']}}</a>'
            ],
            [
                    'title' => 'Amount',
                    'data' => 'amount',
                    'groupable' => true,
                    'sortable' => true,
            ],


    ],
    'editable' => 'true',
    'buttons' => [
            'delete',
    ],
    'buttonsWidth' => '12'
])