@extends('app')
@section('content')
    <div class="container p-3" xmlns:v-on="http://www.w3.org/1999/xhtml" id="container">
        <form action="{{action('ProductController@add')}}" method="post">
            <div class="form-group">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input  type="text" class="form-control"
                            id="name" name="name" required>
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="price">Price</label>
                    <input  type="number" step="0.01" class="form-control"
                            id="price" name="price" required v-model="price">
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input  type="number" class="form-control" step="any" min="0"
                            id="quantity" name="quantity" required v-model="quantity">
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="sum">Sum</label>
                    <span class="form-control" id="sum">@{{sum}}</span>
                </div>
            </div>
            @foreach($users as $user)
                <div>{{$user->name}}</div>
                <label for="user-share-{{$user->id}}">Share:</label>
                <input id="user-share-{{$user->id}}" disabled v-model="users_shares[{{$user->id}}]"/>
                <div class="d-flex btn-group">
                    <div class="btn btn-secondary" @click="users_portions[{{$user->id}}]=Math.max(users_portions[{{$user->id}}]-1, 0)">-</div>
                    <input class="text-center no-padding" type="number" name="users_portions[{{$user->id}}]" v-model="users_portions[{{$user->id}}]"
                           v-model="users_portions[{{$user->id}}]"
                           v-on:input="function(event){
                        if(event.target.value < 0){
                            event.target.value=0;
                            users_portions[{{$user->id}}] = 0;
                        }
                      }"/>
                    <div class="btn btn-secondary" @click="users_portions[{{$user->id}}]++">+</div>
                </div>
            @endforeach
            <multi-select
                    class="mt-3"
                    :options="options"
                    :selected-options="selected"
                    @select="onSelect">
            </multi-select>
            <input type="hidden" v-for="(option, index) in selected" :name="'tags['+index+']'" :value="option.value">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>
    <script>

        const vue = new Vue({
            el: '#container',

            data: function () {
                return {
                    price: '',
                    quantity: 1,
                    options: [@foreach($tags as $tag)
                        {text:'{{$tag->name}}', value:'{{$tag->name}}'},
                    @endforeach],
                    selected: [],
                    users_portions: {
                @foreach($users as $user)
                 {{$user->id}}: 1,
                @endforeach},
                };
            },
            computed: {
                // a computed getter
                sum: function () {
                    return this.price*this.quantity;
                },
                users_shares: function () {
                    var portion_sum = Object.keys(this.users_portions).reduce(
                            (previous, key) => previous + this.users_portions[key], 0);
                    ret = {};
                    for (var key in this.users_portions) {
                        ret[key] = portion_sum!=0 ? this.sum*this.users_portions[key]/portion_sum : 0;
                    }
                    return ret;
                },
            },
            methods: {
                onSelect(items, lastItem, event){
                    this.selected = items;
                }
            }
        });


    </script>

@endsection