<!-- Warning: editable for columns isn't working yet-->
<?php
    $options = [
            'items' => $products,
            'singular' => 'product',
            'plural' => 'products',
            'urlBase' => 'products',
            'columns' => [
                    [
                            'title' => 'Name',
                            'data' => 'name',
                            'width' => '25',
                            'sortable' => true,
                            'groupable' => true,
                            'editable' => true,
                    ],
                    [
                            'title' => 'Price',
                            'data' => 'price',
                            'sortable' => true,
                            'width' => '5',
                    ],
                    [
                            'title' => 'Quant.',
                            'data' => 'quantity',
                            'sortable' => true,
                            'editable' => true,
                            'width' => '5',
                    ],
                    [
                            'title' => 'Sum',
                            'data' => 'sum',
                            'width' => '5',
                            'sortable' => true,
                            'aggregators' => [],

                    ],
                    [
                            'title' => 'Date',
                            'data' => 'created_at',
                            'sortable' => true,
                            'groupable' => true,
                            'width' => '9',
                    ],


            ],
            'editable' => 'true',
            'buttons' => [
                    'delete',
            ],
            'buttonsWidth' => '7'

    ];
foreach($users as $user){
    array_push($options['columns'], [
            'title' => $user->name,
            'data' => 'user_column_'.$user->id,
            'width' => '9',
            'alignment' => 'center',
            'template' => '<div class="d-flex btn-group">
                    <button class="btn btn-secondary" @click="cell.row.'
                    .'user_column_'.$user->id.' = Math.max(--cell.row.'.'user_column_'.$user->id.', 0)">-</button>
                    <div>
                    <input class="text-center no-padding" type="number" v-model="cell.row[\''.'user_column_'.$user->id.'\']"
                     v-model="cell.row[\''.'user_column_'.$user->id.'\']"
                     v-on:input="function(event){
                        if(event.target.value < 0){
                            event.target.value=0;
                            cell.row[\''.'user_column_'.$user->id.'\'] = 0;
                        }
                      }"/>
                    </div>
                    <button class="btn btn-secondary" @click="cell.row.'.'user_column_'.$user->id.'++">+</button>
                    </div>',
            'sortable' => true
    ]);
}

array_push($options['columns'], [
        'title' => 'Tags',
        'data' => 'tag',
        'width' => '13',
        'template' => '<div class="container">
                        <div class="row">
                        <multi-select
                             :options="options"
                             :selected-options="cell.row[\'tag\']"
                             @select="onSelect(cell.row[\'id\'], ...arguments)">
                        </multi-select>
                        </div>
                    </div>',

]);
?>
@extends('common.view', $options)
@section('top-content')
    <form action="{{action('ProductController@addTags')}}"  method="post">
        {{csrf_field()}}
        <button class="btn btn-info m-3">Add tags</button>
    </form>
    <div class="card m-2">
        <div class="card-body">
            <h5 class="card-title">Filter</h5>
            <form action="{{action('ProductController@index')}}" method="get">
                <div class="form-group">
                    <label for="checks">Checks</label>
                    <multi-select
                            :options ="checks"
                            :selected-options = "selected_checks"
                            @select="onCheckSelect"></multi-select>
                    <input type="hidden" name="checkids" v-model="checkids"/>
                </div>
                <div class="form-group">
                    <label for="checks">Tags</label>
                    <multi-select
                            :options ="options"
                            :selected-options = "selected_tags"
                    @select="onTagSelect"></multi-select>
                    <input type="hidden" name="tags" v-model="tag_names"/>
                </div>
                <button class="btn btn-primary" type="submit">Apply Filter</button>
            </form>
        </div>
    </div>
@endsection
@section('vue-data')
    addedTags: [],
    deletedTags: [],
    options: {!! json_encode($tags) !!},
    checks: [
    @foreach($checks as $check)
        { value:{{$check->id}}, text:'{{$check->id}} from {{$check->storeName}} at {{$check->initialDate}} with sum {{$check->initialTotalSum}}'},
    @endforeach
    ],
    selected_checks: [
    @foreach($selected_checks as $check)
        { value:{{$check->id}}, text:'{{$check->id}} from {{$check->storeName}} at {{$check->initialDate}} with sum {{$check->initialTotalSum}}'},
    @endforeach
    ],
    selected_tags: [
    @foreach($selected_tags as $tag)
        { value:'{{$tag->name}}', text:'{{$tag->name}}'},
    @endforeach
    ],
@endsection
@section('vue-computed')
    checkids: function() {
        if(!this.selected_checks)
                return '';
        return this.selected_checks.map(e => e.value).join(",");
    },
    tag_names: function() {
        if(!this.selected_checks)
        return '';
        return this.selected_tags.map(e => e.value).join(";");
    },
@endsection
@section('vue-update-data')
    addedTags: this.addedTags,
    deletedTags: this.deletedTags,
@endsection
@section('vue-methods')
    onSelect (id, items, lastItem, event) {
        item = this.items.find((el)=>{return el.id==id});
         item.tag = items;
        var arrToAdd, arrToDel;
        if(event == 'insert'){
            arrToAdd = this.addedTags;
            arrToDel = this.deletedTags;
            console.log(lastItem);

            @foreach($users as $user)
                item['user_column_{{$user->id}}'] = lastItem.users.find(function(el){return el.id == {{$user->id}};}).pivot.portion;
                console.log()
            @endforeach

        } else if (event == 'delete'){
            arrToAdd = this.deletedTags;
            arrToDel = this.addedTags;
        }
        idx = arrToDel.findIndex(el => el.product_id == id && el.tag == lastItem.text);
        if(idx>=0){
            arrToDel.splice(idx, 1);
        } else {
            arrToAdd.push({product_id: id, tag:lastItem.text});
        }

    },
    removeTag: function(item, tag){
        item['tag']=item['tag'].replace(tag+';', '');
        this.deletedTags.push({product_id: item['id'], tag: tag});
        console.log(this.deletedTags);
    },
    onCheckSelect: function(items, lastItem, event) {
        this.selected_checks = items;
    },
    onTagSelect: function(items, lastItem, event) {
        this.selected_tags = items;
    },
@endsection
@section('vue-update-data')
    addedTags: this.addedTags,
    deletedTags: this.deletedTags,
@endsection
@section('vue-update-success')
    this.addedTags = [];
    this.deletedTags = [];
@endsection
