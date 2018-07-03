@extends('common.view', [
    'items' => $users,
    'singular' => 'user',
    'plural' => 'users',
    'urlBase' => 'users',
    'columns' => [
             [
                    'title' => 'ID',
                    'data' => 'id',
                    'width' => '5',
            ],
            [
                    'title' => 'Name',
                    'data' => 'name',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Brought',
                    'data' => 'brought',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Spent',
                    'data' => 'spent',
                    'sortable' => true,
                    'groupable' => true,
            ],
            [
                    'title' => 'Balance',
                    'data' => 'balance',
                    'sortable' => true,
                    'groupable' => true,
            ],

    ],
    'editable' => 'false',
    'buttons' => [
            'delete',
    ],
    'buttonsWidth' => '12'
])