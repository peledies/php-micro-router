<?php

class Router {

  function __construct(){
    $this->process_input();
    $this->get_method();
  }

  private function process_input(){    
    $this->request = implode('/', array_slice( explode('/', $_SERVER['REQUEST_URI']) , $this->process_index()));         
  }

  private function process_index(){
    // get the directory where the router resides
    $pwd = array_pop(explode('/', __DIR__));
    // find the index of that directory in the request URI
    $index = array_search($pwd, explode('/', $_SERVER['REQUEST_URI']));
    return $index;
  }

  private function get_method(){
    $this->method = $_SERVER['REQUEST_METHOD'];
  }

  public function execute(){
    $match = false;
    foreach ($this->list_routes() as $key => $route) {
      if($route[2] == $this->method && $route[0] == $this->request){
        $func = $this->routes[$key][1];
        $func();
        $match = true;
        break;
      }
    }
    if(!$match){
      echo "No routes found matching this path";
    }
  }

  private function list_routes(){
    return $this->routes;
  }

  private function add_route($route){
    $this->routes[] = $route;
  }

  public function get($route){
    $route[] = 'GET';
    $this->add_route($route);
  }

  public function put($route){
    $route[] = 'PUT';
    $this->add_route($route);
  }

  public function post($route){
    $route[] = 'POST';
    $this->add_route($route);
  }

  public function delete($route){
    $route[] = 'DELETE';
    $this->add_route($route);
  }

  public function request_body(){
    return json_decode(file_get_contents('php://input'));
  }

}