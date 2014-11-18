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
        $joke = DB::select("SELECT joke.*, GROUP_CONCAT(category.name) as categories FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id WHERE joke.id = '$id'");
        $categories = $this->getCategories();
        return View::make('joke.index', array('joke' => $joke[0], 'categories' => $categories));
    }

    /**
     * 
     * @return array
     */
    private function getCategories() {
        return DB::select("SELECT name, id, main FROM category ORDER BY name");
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
            $results->items = DB::select("SELECT joke.*, GROUP_CONCAT(category.name) as categories FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id WHERE category.name = '$category' GROUP BY joke.id ORDER BY plus_votes - minus_votes DESC LIMIT 10 OFFSET $start");
            $totalItems = DB::select("SELECT COUNT(*) as count FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id WHERE category.name = '$category'");
        } else {
            $results->items = DB::select("SELECT joke.*, GROUP_CONCAT(category.name) as categories FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id GROUP BY joke.id ORDER BY plus_votes - minus_votes DESC LIMIT 10 OFFSET $start");
            $totalItems = DB::select("SELECT COUNT(*) as count FROM joke");
        }
        $results->totalItems = $totalItems[0]->count;

        return $results;
    }
    
    public function addJoke() {
        return View::make('joke.add');
    }
    
    public function editJoke($id) {
        $joke = DB::select("SELECT joke.*, category.name as categories FROM joke JOIN jokecategory ON joke.id = jokecategory.joke_id JOIN category ON jokecategory.category_id = category.id WHERE joke.id = '$id'");        
        $joke = Joke::find(1);
        return View::make('joke.add', array('joke' => $joke));
    }
    
    public function insertJoke() {
        $title = Input::get('title');
        $text = Input::get('text');
        $categoriesSelected = Input::get('categories');
        
        if (empty($categoriesSelected) || empty($text) || empty($title)) {
            return View::make('joke.add', array('message' => "You should fill all inputs.", 'type' => "danger", "title" => $title, "text" => $text));
        }
        
        $jokes = Joke::all();
        foreach ($jokes as $joke) { 
            similar_text(strtoupper($text), strtoupper($joke->text), $similarity_text);
            similar_text(strtoupper($title), strtoupper($joke->title), $similarity_title);
            if (number_format($similarity_text, 0) > 70){ 
                $message = "<strong>The text you entered is too similar to joke:</strong> $joke->text"; 
            } else if (number_format($similarity_title, 0) > 90) {
                $message = "<strong>The title you entered is too similar to title:</strong> $joke->title"; 
            }
            if(isset($message)) {
                return View::make('joke.add', array('message' => $message, 'type' => "danger", "title" => $title, "text" => $text));
            }
        }
 
        $joke = new Joke();
        $joke->title = $title;
        $joke->text = $text;
        $joke->save();
              
        $joke->categories()->attach($categoriesSelected);

        return View::make('joke.add', array('message' => "successfull",  'type' => "success"));
    }
    
    private function isTitleDuplicit($title) {
        $jokes = Joke::all();
        foreach ($jokes as $joke) { 
            similar_text(strtoupper($title), strtoupper($joke->title), $similarity_title);
            if (number_format($similarity_title, 0) > 90) {
                return true;
            }
        }
        return false;    
    }
    
    private function isTextDuplicit($text) {
        $jokes = Joke::all();
        foreach ($jokes as $joke) { 
            similar_text(strtoupper($text), strtoupper($joke->text), $similarity_text);
            if (number_format($similarity_text, 0) > 70){ 
                return true;
            }
        }
        return false;
    }
    
    public function checkTitleDuplicity() {
        $title = Input::get('title');
        return json_encode($this->isTitleDuplicit($title));
    }
        
    public function checkTextDuplicity() {
        $text = Input::get('text');
        return json_encode($this->isTextDuplicit($text));
    }

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
