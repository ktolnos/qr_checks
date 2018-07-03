<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('tags/index', [
            'tags' => $this->getTagsTree(),
            'users' => User::all()
        ]);
    }

    public function store(Request $request){
        foreach($request->deleted as $tagName){
            Tag::deleteByName($tagName);
        }
        foreach($request->modified as $oldName => $new){
            Tag::changeName($oldName, $new['name']);
            foreach($new['users'] as $user){
                DB::table('tag_user')->where('tag_name', $new['name'])->where('user_id', $user['id'])
                    ->update(['portion' => $user['pivot']['portion']]);
            }
        }
        foreach($request->added as $tag){
            $parentName = null;
            if($tag['name'] == ''){
                continue;
            }
            if(array_key_exists('parentName', $tag)){
                $parentName = $tag['parentName'];
            }
            echo 'insert '.$tag['name'].' as child of '.$parentName;
            Tag::insert($tag['name'], $parentName);
            foreach($tag['users'] as $user){
                DB::table('tag_user')->where('tag_name', $tag['name'])->where('user_id', $user['id'])
                    ->update(['portion' => $user['pivot']['portion']]);
            }
        }

    }


    private function getTagsTree(){
        $allTags = Tag::all();
        $tags = [];
        $i = 0;
        $parent = null;
        $this->addChildrenTo($parent, $allTags, $i, $tags);
        return $tags;
    }

    private function addChildrenTo($parent, &$allItems, &$i, &$newItems){
        while($i<count($allItems)){
            $it = $allItems[$i];
            $it_obj = (object) array(
                'name' => $it->name,
                'depth' => $it->depth,
                'parentName' => $it->parent_name,
                'users' => $it->users()->get(),
                'children' => [],)
            ;
            if(is_null($parent)&&$it->depth==0){
                $newItems[] = $it_obj;
                $i++;
                $this->addChildrenTo($it_obj, $allItems, $i, $newItems);
                continue;
            }

            if($it->depth == $parent->depth+1){
                $parent->children[] = $it_obj;
                $i++;
                $this->addChildrenTo($it_obj, $allItems, $i, $newItems);
                continue;
            }
            return;
        }
    }


}
