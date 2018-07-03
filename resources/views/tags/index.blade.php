@extends('app')

@section('content')
    <script type="text/x-template" id="item-template">
        <li>
            <div class="col-md-9 pl-0 row" :class="{bold: isFolder, 'primary': isFolder, 'secondary': !isFolder}">

                <div class="input-group col-md-6">
                    <div class="input-group-prepend"  v-if="isFolder">
                        <button @click="toggle" class="btn btn-outline-secondary" type="button">[@{{ open ? '-' : '+' }}]</button>
                    </div>
                    <div class="input-group-prepend"  v-if="!isFolder">
                        <span class="input-group-text" id="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <input type="text" class="form-control" :class="{'border border-danger text-danger': hasErrors}" v-model="model.name" @change="onChange">
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger" type="button" @click="deleteThis" title="delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-info" type="button" @click="changeType" title="make folder">
                        <i class="fas fa-folder"></i>
                        </button>
                    </div>
                </div>
                <div  v-for="user in model.users" class="input-group col-md-2">
                    <span class="input-group-text input-group-prepend py-0 my-0">@{{user.name}}</span>
                    <input :id="'user_portion_'+user.id" type="number" class="form-control py-0 my-0" v-model="user.pivot.portion" @change="onChange"/>
                </div>
            </div>
            <ul v-show="open" v-if="isFolder">
                <item
                        class="item"
                        v-for="(model, index) in model.children"
                        :key="index"
                        :model="model">
                </item>
                <li class="add"><button class="btn btn-outline-success" @click="addChild">+</button></li>
            </ul>
        </li>
    </script>
    <div id="index">
        <button class="btn btn-primary" @click="save">Save</button>
        <p>(You can double click on an item to turn it into a folder.)</p>

        <!-- the demo root element -->
        <ul class="tree">
            <item
                    class="item"
                    v-for="(model, index) in treeData"
                    :model="model">
            </item>
            <li class="add"><button class="btn btn-outline-success" @click="addItem">+</button></li>
        </ul>
    </div>

    <script>
        // demo data
        var data = {!! json_encode($tags) !!}

        var added = [], deleted = [], modified = {};

        // define the item component
        Vue.component('item', {
            template: '#item-template',
            props: {
                model: Object
            },
            data: function () {
                return {
                    open: true,
                    hasErrors: false,
                }
            },
            computed: {
                isFolder: function () {
                    return this.model.children &&
                            this.model.children.length
                }
            },
            methods: {
                toggle: function () {
                    if (this.isFolder) {
                        this.open = !this.open
                    }
                },
                changeType: function () {
                    if (!this.isFolder) {
                        Vue.set(this.model, 'children', []);
                        this.addChild();
                        this.open = true;
                    }
                },
                addChild: function () {
                    var len = this.model.children.push({
                        parentName: this.model.name,
                        name: '',
                        users: [
                                @foreach($users as $user)
                            {
                                id: {{$user->id}},
                                name: '{{$user->name}}',
                                pivot: {
                                    portion: 1
                                }
                            },
                                @endforeach
                        ],

                    });
                    added.push(this.model.children[len-1]);
                },
                deleteThis: function () {
                    var idx = added.indexOf(this.model);
                    if(idx >=0 ){
                        added.splice(idx, 1);
                    } else {
                        deleted.push(this.model.initialName);
                    }
                    if(this.$parent.model){
                        this.$parent.model.children.splice(this.$parent.model.children.indexOf(this.model),1);
                    } else {
                        this.$parent.treeData.splice(this.$parent.treeData.indexOf(this.model), 1);
                    }
                },
                onChange: function (){
                    if(this.model.name.includes(';')){
                        this.hasErrors = true;
                    } else {
                        this.hasErrors = false;
                    }
                    if(this.model.initialName !== undefined) {
                        modified[this.model.initialName] = {
                            name: this.model.name,
                            users: this.model.users
                        };
                    }
                },
                log: function(arg) {
                    console.log(arg);
                }
            }
        });

        function addInitialName(data){
            if(!data){
                return;
            }
            data.forEach((el) => {
                el.initialName = el.name;
                addInitialName(el.children);
            })
        }

        addInitialName(data);

        // boot up the demo
        var vue = new Vue({
            el: '#index',
            data: {
                treeData: data,
            },
            methods: {
                addItem: function () {
                    len = this.treeData.push({
                        name: '',
                        users: [
                        @foreach($users as $user)
                        {
                            id: {{$user->id}},
                            name: '{{$user->name}}',
                            pivot: {
                                portion: 1
                            }
                        },
                        @endforeach
                        ],
                    });
                    added.push(this.treeData[len-1]);
                },
                save: function (){
                    console.log('added');
                    console.log(added);
                    console.log('modified');
                    console.log(modified);
                    console.log('deleted');
                    console.log(deleted);
                    axios.post('/tags/store',
                            {
                                added: added,
                                modified: modified,
                                deleted: deleted,
                            }).then(data => {
                                console.log(data);
                                addInitialName(this.treeData);
                                added = [];
                                modified = {};
                                deleted = [];
                            });
                },

            }

        })

        window.onbeforeunload = function() {
            var saved = added.length == 0 && deleted.length == 0 && Object.keys(modified).length == 0;
            console.log(saved);
            return saved?null:"Changes are not saved!";
        };
    </script>
@endsection
