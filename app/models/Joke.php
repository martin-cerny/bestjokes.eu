<?php

class Joke extends Eloquent {
    
    use SoftDeletingTrait;

    protected $table = 'joke';
    protected $fillable = array('title', 'text', 'plus_votes', 'minus_votes');
    protected $guarded = array('id');
    protected $dates = ['deleted_at'];
    
    public function categories() {
        return $this->belongsToMany('Category', 'jokecategory');
    }
    
    /**
     * Get results by page
     *
     * @param int $page
     * @param String $category
     * @return StdClass
     */
    public static function getByPage($page = 0, $category = 0) {
        if($page != 0) {
            $start = ($page-1) * 10;
        } else {
            $start = 0;
        }
        $results = new stdClass();
        $results->page = $page;
        $results->limit = 10;
        $results->totalItems = 0;
        $results->items = array();
        $totalItems = 0;

        if ($category != '') {
            $results->items = DB::select("SELECT joke.*, GROUP_CONCAT(category.name) as categories "
                    . "FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id "
                    . "WHERE joke.id in ("
                        . "SELECT joke.id "
                        . "FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id "
                        . "WHERE category.name = '$category')"
                        . "AND joke.deleted_at = '0000-00-00' "
                    . "GROUP BY joke.id "
                    . "ORDER BY plus_votes - minus_votes DESC "
                    . "LIMIT 10 OFFSET $start");
            $totalItems = DB::select("SELECT COUNT(*) as count "
                    . "FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id "
                    . "WHERE category.name = '$category' AND joke.deleted_at = '0000-00-00'");
        } else {
            $results->items = DB::select("SELECT joke.*, GROUP_CONCAT(category.name) as categories "
                    . "FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id "
                    . "WHERE joke.deleted_at = '0000-00-00' "
                    . "GROUP BY joke.id "
                    . "ORDER BY plus_votes - minus_votes DESC "
                    . "LIMIT 10 OFFSET $start");
            $totalItems = DB::select("SELECT COUNT(*) as count FROM joke WHERE joke.deleted_at = '0000-00-00'");
        }
        $results->totalItems = $totalItems[0]->count;

        return $results;
    }
    
        /**
     * 
     * @param type $title
     * @return boolean
     */
    public static function isTitleDuplicit($title) {
        $jokes = Joke::all();
        foreach ($jokes as $joke) { 
            similar_text(strtoupper($title), strtoupper($joke->title), $similarity_title);
            if (number_format($similarity_title, 0) > 90) {
                return true;
            }
        }
        return false;    
    }
    
    
    /**
     * 
     * @param type $text
     * @return boolean
     */
    public static function isTextDuplicit($text) {
        $jokes = Joke::all();
        foreach ($jokes as $joke) { 
            similar_text(strtoupper($text), strtoupper($joke->text), $similarity_text);
            if (number_format($similarity_text, 0) > 70){ 
                return true;
            }
        }
        return false;
    }
    
    public static function getById($id) {
        return DB::select("SELECT joke.*, GROUP_CONCAT(category.name) as categories FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id WHERE joke.id = '$id'");
    }
            

}