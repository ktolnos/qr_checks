@extends('app')

@section('content')
    <a class="btn btn-primary" href="{{url($urlBase.'/add')}}">Add {{$singular}}</a>
    @if (count($items) > 0)
        <div id="datatables" class="panel panel-default">
            <div class="panel-heading">
                {{ucfirst($plural)}}
            </div>
            <div class="grid-row" layout="row top-stretch">
                <div class="grid-cell">
                    <datatable id="data-table-main"
                               :source="items.rows"
                               :striped="items.striped"
                               :editable="items.editable"
                               :line-numbers="items.lineNumbers">
                        <datatable-column
                                v-for="item in items.columns"
                                :id="item.id"
                                :label="item.label"
                                :width="item.width"
                                :sortable="item.sortable"
                                :groupable="item.groupable"
                                :aggregators="item.aggregators"
                                :formatter="item.formatter">
                        </datatable-column>
                    </datatable>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-striped task-table">

                    <!-- Table Headings -->
                    <thead>
                    @foreach($titles as $title)
                        <th>{{$title}}</th>
                    @endforeach
                    @if($buttonsColumn)
                        <th>&nbsp;</th>
                    @endif
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                    @foreach ($items as $item)
                        @include($rowTemplate, ['params' => $rowParams])
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="panel-heading">
            No {{ucfirst($plural)}}
        </div>
    @endif


    <script>
        var aggregators = vuetiful.aggregators;
        var formatters = vuetiful.formatters;
        var currencies = vuetiful.maps.currencies;

        var items = {
            striped: true,
            editable: true,
            lineNumbers: false,
            filter: null,

            currency: "RUR",
            dateFormat: "DD MMMM YYYY",

            columns: [
                {
                    id: "name",
                    label: "Name",
                    width: null,
                    sortable: true,
                    groupable: true,
                    aggregators: []
                },
                {
                    id: "qauntity",
                    label: "Quantity",
                    width: 25,
                    sortable: true,
                    groupable: true,
                    aggregators: []
                },
                {
                    id: "price",
                    label: "Price",
                    width: null,
                    sortable: true,
                    groupable: true,
                    aggregators: [
                        aggregators.total
                    ],
                    formatter: function (value) {
                        return formatters.currency(value, 2, this.currency);
                    }
                },
                {
                    id: "sum",
                    label: "Sum",
                    width: null,
                    sortable: true,
                    groupable: true,
                    aggregators: [
                        aggregators.total
                    ],
                    formatter: function (value) {
                        return formatters.currency(value, 2, this.currency);
                    }
                }
            ],

            rows: {!! json_encode($items) !!},

            selected: []
        };

        new Vue({
            el: "#datatables",

            data: function () {
                return {
                    items: items,
                    currencies: currencies,
                    aggregators: aggregators,
                    dateFormats: [
                        "DD/MM/YYYY",
                        "DD MMM YYYY",
                        "D MMMM YYYY",
                        "D/MM/YYYY h:mm a"
                    ],
                    formatters: [
                        {id: "C", name: "Currency"},
                        {id: "DT", name: "Date and Time"}
                    ]
                };
            },

            computed: {

                selectAll: {
                    get: function () {
                        return customers.selected.length == customers.rows.length;
                    },
                    set: function (value) {
                        customers.selected = value ? customers.rows : [];
                    }
                }

            },

            methods: {
                deleteCustomer: function (customer) {
                    var result = window.confirm("You are about to delete " + customer.purchasor_name + ". Are you sure?");

                    if (result) {
                        var index = customers.rows.indexOf(customer);

                        if (index === -1) {
                            return;
                        }

                        customers.rows.splice(index, 1);
                    }
                }
            }
        });

    </script>
@endsection