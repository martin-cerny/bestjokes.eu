<?php

class AdminController extends BaseController {
    
    public function login(){
        return Redirect::route('homepage');
    }
}