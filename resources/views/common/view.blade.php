@extends('app')

@section('content')
    <div id="content" xmlns="http://www.w3.org/1999/html">
        <a class="btn btn-outline-primary" href="{{url($urlBase.'/add')}}">Add {{$singular}}</a>
        <button :class="'save btn btn-primary '+saveButtonClass" @click="save(items, deletedItems)">Save</button>
        @yield('top-content')
        @if (count($items) > 0)
            <div id="datatables" class="panel panel-default">
                <div class="panel-heading">
                    {{ucfirst($plural)}}
                </div>
                <div class="panel-body">
                    <paginator :source="items" :page-size="50">
                        <template scope="page">
                            <!-- Notice here how we bind the datatable source to the data property exposed from the paginator -->
                            <!-- this ensures that the only data the datatable is aware of is the current page -->
                            <datatable id="data-table-main"
                                       :source="page.data"
                                       :striped="true"
                                       :editable={{$editable}}>

                                <!--TODO Editable for columns -->

                                @foreach($columns as $column)

                                    <datatable-column
                                            @if(!isset($column['template']))
                                            :id="'{{$column['data']}}'"
                                            @else
                                            id="{{isset($column['id'])?$column['id']:'column_'.$column['title']}}"
                                            @endif
                                            @isset($column['formatter'])
                                            :formatter="{{$column['formatter']}}"
                                            @endisset
                                            :label="'{{$column['title']}}'"
                                            :width="{{$column['width'] or 'null'}}"
                                            :sortable="{{isset($column['sortable'])?json_encode($column['sortable']):json_encode(false)}}"
                                            :groupable="{{isset($column['groupable'])?json_encode($column['groupable']):json_encode(false)}}"
                                            :editable="{{isset($column['editable'])?json_encode($column['editable']):json_encode(false)}}"
                                            @isset($column['aggregators'])
                                            :aggregators="[
                                            @foreach($column['aggregators'] as $aggregator)
                                                    aggregators.{{$aggregator}},
                                            @endforeach]"
                                            @endisset
                                            >
                                        {{$column['view'] or ''}}
                                    </datatable-column>
                                    @isset($column['template'])
                                    <template slot="{{isset($column['id'])?$column['id']:'column_'.$column['title']}}" scope="cell">
                                        {!! $column['template'] !!}
                                    </template>
                                    @endisset
                                @endforeach
                                @if(count($buttons) > 0)
                                    <datatable-column id="actions" label="Actions" :sortable="false" :groupable="false"
                                                      :width="{{$buttonsWidth}}"></datatable-column>
                                    <template slot="actions" scope="cell">
                                        <div class="btn-group d-flex" role="group">
                                            @foreach($buttons as $button_name => $button_props)
                                                <?php
                                                    $defaults = [
                                                        'delete' => [
                                                            'title' => 'Delete',
                                                            'onclick' => "deleteItem(items, cell.row, '')",
                                                            'class' => 'btn-danger'
                                                        ],
                                                        'view' => [
                                                            'title' => 'View',
                                                            'onclick' => "viewItem(cell.row, 'id')",
                                                            'class' => 'btn-secondary'
                                                        ]
                                                    ];
                                                    if(is_string($button_props) && array_key_exists($button_props, $defaults)){
                                                        $button_props = $defaults[$button_props];
                                                    }
                                                ?>
                                                <a class="btn
                                                @if(array_key_exists('class', $button_props)) {{$button_props['class']}}
                                                @else 'btn-secondary'
                                                @endif
                                                        col-md-12"
                                                @if(array_key_exists('onclick', $button_props))
                                                    @click="{{$button_props['onclick']}}"
                                                @endif
                                                @if(array_key_exists(':href', $button_props))
                                                    :href="{{$button_props[':href']}}"
                                                @endif

                                                    >
                                                    {{$button_props['title']}}
                                                    </a>
                                            @endforeach

                                        </div>
                                    </template>
                                @endif
                            </datatable>
                        </template>
                    </paginator>
                    <button :class="'save static btn '+saveButtonClass" @click="save(items, deletedItems)"><i class="fas fa-save"></i></button>
                </div>
            </div>
        @else
            <div class="panel-heading">
                No {{ucfirst($plural)}}
            </div>
        @endif

    </div>

    <script>

        var aggregators = vuetiful.aggregators;

        const datatable = new Vue({
            el: '#content',

            data: function () {
                return {
                    items: {!! json_encode($items) !!},
                    deletedItems: [],
                    aggregators: aggregators,
                    modified: false,
                    saveButtonClass: 'btn-primary',
                    @yield('vue-data')
                };
            },
            computed: {
              @yield('vue-computed')
            },
            watch: {
                items: {
                    handler: function (val, oldVal) {
                        this.modified = true;
                    },
                    deep: true
                }
            },
            methods: {
                deleteItem: function (rows, item, itemPropName) {
                    var result = window.confirm("You are about to delete " + item[itemPropName] + ". Are you sure?");

                    if (result) {
                        var index = rows.indexOf(item);

                        if (index === -1) {
                            return;
                        }
                        this.deletedItems.push(rows[index]);
                        console.log(this.deletedItems);

                        rows.splice(index, 1);
                    }
                },
                viewItem: function (item, itemPropId) {
                    window.location = "{{$urlBase.'/show'}}/"+item[itemPropId];
                },
                save: function (items, deletedItems){
                    this.saveButtonClass = 'btn-warning';
                    axios.post('{{$urlBase}}/update',
                            {
                                items: items,
                                deletedItems:deletedItems,
                                @yield('vue-update-data')
                            }).then(data => {
                                console.log(data);
                                this.modified = false;
                                this.saveButtonClass = 'btn-success';
                                setTimeout(()=>{this.saveButtonClass = 'btn-primary';}, 2000);
                                @yield('vue-update-success')
                    });
                },
                log: function(item){
                    window.console.log(item);
                },
                @yield('vue-methods')
            },

        });


        window.onbeforeunload = function() {
            return datatable.modified?"Changes are not saved!":null;
        };

    </script>
@endsection