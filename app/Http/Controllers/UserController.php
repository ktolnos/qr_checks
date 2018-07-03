<?php

namespace App\Http\Controllers;

use App\User;
use App\UserView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Validator;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $users = UserView::all();
        return view('users/index', [
            'users' => $users,
        ]);
    }

    public function update(Request $request){
        $keys = [];
        foreach($request->deletedItems as $item){
            array_push($keys, $item['id']);
        }
        User::destroy($keys);
    }

    public function add(){
        $action = URL::route('user.store');
        return view('users/add', ['action'=>$action]);
    }

    public function edit($id){
        $action = URL::route('user.update', ['id' => $id]);
        $user = User::find($id);
        return view('users/add', compact($action, $user));
    }


    public function store(Request $request){
        $validator = $this->getValidator($request);
        if($validator->fails()){
            return redirect('users/add')->withErrors($validator);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = 'rand'.rand(0,100000000);
        $user->password = 'mops';
        $user->remember_token = 'mops';
        $user->save();
        return redirect('users/add');
    }

    private function getValidator($request){
        return Validator::make($request->all(), [
            'name' => 'required'
        ]);
    }
}
