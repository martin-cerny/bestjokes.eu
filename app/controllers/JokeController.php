<?php

class JokeController extends BaseController {

    /**
     * 
     * @param type $page
     * @return type
     */
    public function getIndex($page = 0) {
        $categories = Category::getAll();
        
        $data = Joke::getByPage($page);
        Paginator::setCurrentPage($page);
        $jokes = Paginator::make($data->items, $data->totalItems, 10);
        return View::make('jokes.index', array('jokes' => $jokes, 'page' => $page, 'categories' => $categories, 'totalItems' => $data->totalItems));
    }

    /**
     * 
     * @param type $category
     * @param type $page
     * @return type
     */
    public function getCategory($category, $page = 0) {
        $categoryTitle = str_replace('-', ' ', str_replace('-jokes', '', $category));
        $categories = Category::getAll();
        
        $data = Joke::getByPage($page, $categoryTitle);
        Paginator::setCurrentPage($page);
        $jokes = Paginator::make($data->items, $data->totalItems, 10);
        
        return View::make('jokes.index', array('jokes' => $jokes, 'page' => $page, 'categories' => $categories, 'categoryTitle' => $categoryTitle, 'totalItems' => $data->totalItems));
    }
    
    /**
     * Get specific joke
     * @param type $id
     * @return type
     */
    public function getJoke($id) {
        $joke = Joke::getById($id);
        $categories = Category::getAll();
        return View::make('joke.index', array('joke' => $joke[0], 'categories' => $categories, 'type' => 'joke'));
    }

    /**
     * Get all categories
     * @return array
     */
    private function getCategories() {
        return Category::getAll();
    }
    
    /**
     * 
     * @return type
     */
    public function addJoke() {
        return View::make('joke.add');
    }
    
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function editJoke($id) {
        $joke = Joke::with('categories')->findOrFail($id);
        $joke['categories'] = array_pluck($joke['categories'], 'id');
        $previous = Input::get('previous');
        return View::make('joke.add', array('joke' => $joke, 'previous' => $previous));
    }
    
    public function updateJoke($id) {
        $inputs = Input::get();
        $joke = Joke::findOrFail($id);
        DB::beginTransaction();
        try {
            $joke->update($inputs);
            $joke->categories()->detach();    
            $categoriesSelected = Input::get('categories');
            $joke->categories()->attach($categoriesSelected); 
            
            DB::commit();
            return Redirect::to(Input::get('previous'));
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with('fail', 'An error occurred saving the joke. Please contact administrator')->withInput();   
        }  
    }
    
    /**
     * 
     * @return type
     */
    public function insertJoke() {
        $newJoke = new Joke();
        $newJoke->title = Input::get('title');
        $newJoke->text = Input::get('text');
        $categoriesSelected = Input::get('categories');
        
        if (empty($categoriesSelected) || empty($newJoke->text) || empty($newJoke->title)) {
            return View::make('joke.add', array('message' => "You should fill all inputs.", 'type' => "danger", "joke" => $newJoke));
        }
        
        $jokes = Joke::all();
        foreach ($jokes as $joke) { 
            similar_text(strtoupper($newJoke->text), strtoupper($joke->text), $similarity_text);
            similar_text(strtoupper($newJoke->title), strtoupper($joke->title), $similarity_title);
            if (number_format($similarity_text, 0) > 70){ 
                $message = "<strong>The text you entered is too similar to joke:</strong> $joke->text"; 
            } else if (number_format($similarity_title, 0) > 90) {
                $message = "<strong>The title you entered is too similar to title:</strong> $joke->title"; 
            }
            if(isset($message)) {
                return View::make('joke.add', array('message' => $message, 'type' => "danger", "joke" => $newJoke));
            }
        }
        $newJoke->save();
              
        $newJoke->categories()->attach($categoriesSelected);

        return View::make('joke.add', array('message' => "successfull",  'type' => "success"));
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function deleteJoke($id) {
        $joke = Joke::find($id);
        $joke->delete();
        return Redirect::to(Input::get('previous'));
    }
    
    
    /**
     * 
     * @return type
     */
    public function checkTitleDuplicity() {
        $title = Input::get('title');
        return json_encode(Joke::isTitleDuplicit($title));
    }
     
    
    /**
     * 
     * @return type
     */
    public function checkTextDuplicity() {
        $text = Input::get('text');
        return json_encode(Joke::isTextDuplicit($text));
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
