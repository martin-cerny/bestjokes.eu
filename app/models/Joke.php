<?php

class Joke extends Eloquent {

    protected $table = 'joke';
    
    protected $fillable = array('title', 'text', 'plus_votes', 'minus_votes');
    protected $guarded = array('id');
    
    public function categories() {
        return $this->belongsToMany('Category', 'jokecategory');
    }

}