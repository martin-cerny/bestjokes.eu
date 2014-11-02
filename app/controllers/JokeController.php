<?php

class JokeController extends BaseController {

    public function getIndex($page = 0) {
        $categories = $this->getCategories();
        
        $data = $this->getByPage($page);
        Paginator::setCurrentPage($page);
        $jokes = Paginator::make($data->items, $data->totalItems, 10);

        return View::make('jokes.index', array('jokes' => $jokes, 'page' => $page, 'categories' => $categories));
    }

    public function getCategory($category, $page = 0) {
        $categoryTitle = str_replace('-', ' ', str_replace('-jokes', '', $category));
        $categories = $this->getCategories();
        
        $data = $this->getByPage($page, $categoryTitle);
        Paginator::setCurrentPage($page);
        $jokes = Paginator::make($data->items, $data->totalItems, 10);
        
        return View::make('jokes.index', array('jokes' => $jokes, 'page' => $page, 'categories' => $categories, 'categoryTitle' => $categoryTitle));
    }
    
    public function getJoke($id) {
        $joke = DB::select("SELECT *, GROUP_CONCAT(category.name) as categories FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id WHERE joke.id = '$id'");
        $categories = $this->getCategories();
        return View::make('joke.index', array('joke' => $joke[0], 'categories' => $categories));
    }

    /**
     * 
     * @return array
     */
    private function getCategories() {
        return DB::select("SELECT name, id, main FROM category");
    }

    /**
     * Get results by page
     *
     * @param int $page
     * @param String $category
     * @return StdClass
     */
    private function getByPage($page = 0, $category = 0) {
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
            $results->items = DB::select("SELECT joke.*, GROUP_CONCAT(category.name) as categories FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id WHERE category.name = '$category' GROUP BY joke.id LIMIT 10 OFFSET $start");
            $totalItems = DB::select("SELECT COUNT(*) as count FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id WHERE category.name = '$category'");
        } else {
            $results->items = DB::select("SELECT joke.*, GROUP_CONCAT(category.name) as categories FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id GROUP BY joke.id LIMIT 10 OFFSET $start");
            $totalItems = DB::select("SELECT COUNT(*) as count FROM joke");
        }
        $results->totalItems = $totalItems[0]->count;

        return $results;
    }
    
    public function addJoke() {
        $categories = $this->getCategories();
        return View::make('joke.add', array('categories' => $categories));
    }
    
    public function insertJoke() {
        $title = Input::get('title');
        $text = Input::get('text');
        $categoriesSelected = Input::get('categories');
        $categoriesAll = $this->getCategories();
        
        if (empty($categoriesSelected) || empty($text) || empty($title)) {
            return View::make('joke.add', array('categories' => $categoriesAll, 'message' => "You should fill all inputs.", 'type' => "danger", "title" => $title, "text" => $text));
        }
        
        $similarJokes = DB::select("SELECT * FROM joke");
        foreach ($similarJokes as $similarJoke) { 
            similar_text(strtoupper($text), strtoupper($similarJoke->text), $similarity_pst); 
            if (number_format($similarity_pst, 0) > 70){ 
                $message = "<strong>The text you entered is too similar to joke:</strong> $similarJoke->text"; 
                return View::make('joke.add', array('categories' => $categoriesAll, 'message' => $message, 'type' => "danger", "title" => $title, "text" => $text));
            } 
        } 
        $text = DB::connection()->getPdo()->quote($text);
        $title = DB::connection()->getPdo()->quote($title);
        DB::insert("INSERT INTO joke (text, title) VALUES ($text, $title)");
        $lastId = DB::getPdo()->lastInsertId();
        
        foreach ($categoriesSelected as $category) {
            DB::insert("INSERT INTO jokecategory(joke_id, category_id) VALUES ($lastId, $category)");
        }
        return View::make('joke.add', array('categories' => $categoriesAll, 'message' => "successfull",  'type' => "success"));
    }
    
    
    /**
     * 
     * @return type
     */
    public function addVote() {
        $return = array();
        $return['type'] = Input::get('type');
        $return['id'] = Input::get('id');
        $columnType = $return['type'] . '_votes';
        $columnId = $return['id'];
        DB::update("UPDATE joke SET $columnType = $columnType + 1 WHERE id = $columnId");
        return json_encode($return);
    }

}
