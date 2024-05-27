<?php

class Signup extends Controller {
  public function index() {		
    $this->view('signup/index');
  }

  public function create() {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $passwordConfirm = $_REQUEST['password_confirm'];

    $user = $this->model('User');
    $user->create($username, $password, $passwordConfirm); 
  }
}
