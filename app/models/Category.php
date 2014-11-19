<?php

class Category extends Eloquent {

    protected $table = 'category';
    
    protected $guarded = array('id', 'name', 'main');
    
    public function jokes() {
        return $this->belongsToMany('Joke', 'jokecategory');
    }
    
    public static function getAll() {
        return DB::select("SELECT name, id, main FROM category ORDER BY name");
    }

}