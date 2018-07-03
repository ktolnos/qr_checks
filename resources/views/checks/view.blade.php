@extends('common.view', [
    'items' => $checks,
    'singular' => 'check',
    'plural' => 'checks',
    'urlBase' => 'checks',
    'columns' => [
             [
                    'title' => 'ID',
                    'data' => 'id',
                    'width' => '5',
            ],
            [
                    'title' => 'Fiscal sign',
                    'data' => 'fiscalSign',
                    'width' => '20',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Date',
                    'data' => 'initialDate',
                    'width' => '15',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Date added',
                    'data' => 'created_at',
                    'width' => '15',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Store',
                    'data' => 'storeName',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Initial Total',
                    'data' => 'initialTotalSum',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Actual Total',
                    'data' => 'actualTotalSum',
                    'sortable' => true,
                    'groupable' => true,
            ]


    ],
    'editable' => 'false',
    'buttons' => [
            [
                'title' => 'Pay',
                ':href' => "'/pay/for/check/'+cell.row['id']",
                'class' => 'btn-info'
            ],
            'view',
            'delete',
    ],
    'buttonsWidth' => '16'
])

@section('vue-methods')
@endsection