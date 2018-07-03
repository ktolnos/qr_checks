<?php

namespace App\Http\Controllers;

use App\CheckView;
use App\Product;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use PhpParser\Node\Stmt\Use_;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('id', 'asc');
        $selected_checks = [];
        if (!empty($request->checkids)){
            $selected_ids =  explode(',', $request->checkids);
            foreach ($selected_ids as $check_id) {
                $check = CheckView::find($check_id);
                if($check)
                    array_push($selected_checks, $check);
            }
            $products = $products->whereIn('check_id', $selected_ids);
        }
        $selected_tags = [];
        if (!empty($request->tags)){
            $tags =  explode(';', $request->tags);
            foreach ($tags as $tag) {
                $sel_tag = Tag::find($tag);
                if($sel_tag)
                    array_push($selected_tags, $sel_tag);
            }
            $tags_with_parents = [];
            foreach($selected_tags as $tag){
                $tags_with_parents = array_merge($tags_with_parents, $tag->getChildrenNames());
            }
            $products->whereHas('tags', function($q) use($tags_with_parents)
            {
                $q->whereIn('name', $tags_with_parents);
            });
        }

        $products = $products->get();

        $users = User::all();

        foreach($products as $product){
            foreach($product->users as $user){
                $id = 'user_column_'.$user->id;
                $product->$id = $user->pivot->portion;
            }
            $tagNames = [];
            foreach($product->tags as $tag){

                $tagNames[] = (object) [
                    'text' => $tag->name,
                    'value' => $tag->name,
                    'users' => $tag->users,
                ];
            }
            $product->tag = $tagNames;
        }

        $tags = Tag::all();
        foreach($tags as $tag){
            $tag->text = $tag->name;
            $tag->value = $tag->name;
            $tag->users = $tag->users()->get();
        }

        $checks = CheckView::all();

        return view('products/index', [
            'products' => $products,
            'users' => $users,
            'tags' => $tags,
            'checks' => $checks,
            'selected_checks' => $selected_checks,
            'selected_tags' => $selected_tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $validator = null;
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'quantity' => 'required|numeric',
            ]);

            if (!$validator->fails()) {
                $product = new Product();
                $product->name = $request->name;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->sum = $request->price*$request->quantity;
                $product->save();
                foreach($product->users as $user){
                    $user->pivot->portion = $request->users_portions[$user->id];
                    $user->pivot->save();
                }
                if(isset($request->tags)) {
                    foreach ($request->tags as $tag) {
                        $product->tags()->attach($tag);
                    }
                }
                $product->save();
                return redirect('/products');
            }
        }
        $users = User::all();
        $tags = Tag::all();
        return view('products/add', [
            'users' => $users,
            'tags' => $tags,
        ])->withErrors($validator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        foreach($request->items as $item){
            $product = Product::find($item['id']);
            $product->name = $item['name'];
            if(is_numeric($item['sum'])){
                $product->sum = $item['sum'];
                $product->quantity =  $item['quantity'];
                $product->price =  $item['sum']/$item['quantity'];
            }
            $product->save();
            foreach($product->users as $user){
                $user->pivot->portion = $item['user_column_'.$user->id];
                $user->pivot->save();
            }
        }
        $keys = [];
        foreach($request->deletedItems as $item){
            array_push($keys, $item['id']);
        }
        Product::destroy($keys);
        foreach($request->addedTags as $prod_tag){
            $product = Product::find($prod_tag['product_id']);
            $product->tags()->attach($prod_tag['tag']);
        }
        foreach($request->deletedTags as $prod_tag){
            $product = Product::find($prod_tag['product_id']);
            $product->tags()->detach($prod_tag['tag']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (isset($request->items)){
            print_r($request->items);
        } else {
            echo "No items passed!";
        }
    }

    public function addTags(){
        DB::unprepared('call ADD_TAGS_TO_PRODUCTS();');
        return redirect('/products');
    }
}
