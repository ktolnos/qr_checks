<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    protected $table = 'computed_tag_tree';
    protected $primaryKey = 'name';
    public $incrementing = false;

    public static function insert($newName, $parentName){
        DB::statement('call INSERT_TAG(:newName, :parentName);', ['newName'=>$newName, 'parentName'=>$parentName]);
    }

    public function delete(){
        DB::statement('call DELETE_TAG(:name);', ['name'=>$this->name]);
    }

    public static function deleteByName($name){
        DB::statement('call DELETE_TAG(:name);', ['name'=>$name]);
    }

    public static function changeName($oldName, $newName){
        DB::table('tags')
            ->where('name', $oldName)
            ->update(['name' => $newName]);
    }

    public function getChildrenNames(){
        $tag = DB::table('tags')->where('name', $this->name)->first();
        if(!$tag)
            return [];
        $tags = DB::table('tags')
            ->where('lft', '>=', $tag->lft)
            ->where('rgt', '<=', $tag->rgt)
            ->get();
        $ret = [];
        foreach($tags as $tag){
            array_push($ret, $tag->name);
        }
        return $ret;
    }

    public function users(){
        return $this->belongsToMany('App\User', 'tag_user', 'tag_name', 'user_id')->withPivot('portion');
    }
}
