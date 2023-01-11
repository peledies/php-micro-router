<?php

trait pprint {
  private function pprint($data) {
    print("<pre>".print_r($data, true)."</pre>");
  }
}

trait tokenizer
{
  private function tokenizer($path) : iterable{
    $tokens = explode('/', $path);

    // remove the first element of the array
    // because it will always be empty
    array_shift($tokens);

    return $tokens;
  }
}

class Routable {
  use tokenizer;
  use pprint;

  public function __construct(String $method, String $path){
    $this->url = $path;
    $this->tokenized_url = $this->tokenizer($path);
    $this->method = $method;
  }
}

class Request extends Routable{
  public function __construct(String $method, String $path, $data){
    parent::__construct($method, $path);
    $this->body = $data;
  }
}

class Route extends Routable{
  public function __construct(String $method, String $path, String $function){
    parent::__construct($method, $path);
    $this->function = $function;
  }
}

class Router {

  use tokenizer;
  use pprint;

  function __construct(){
    $this->request = new Request(
      $_SERVER['REQUEST_METHOD'],
      $_SERVER['REQUEST_URI'],
      $this->extract_request_body()
    );

    $this->tokenized_url = $this->tokenizer($_SERVER['REQUEST_URI']);
    $this->url = $_SERVER['REQUEST_URI'];
    $this->method = $_SERVER['REQUEST_METHOD'];
  }

  function noRouteFound() : void {
    http_response_code(404);
    echo json_encode(['status' => 404, 'message' => 'NO ROUTE FOUND']);
    return;
  }

  private function get_routes_by_method() : iterable {
    return array_filter($this->routes, function($route) {
      return $route->method == $this->method;;
    });
  }

  private function filter_routes_by_tokenized_url_length(Array $routes) : iterable {
    return array_filter($routes, function($route) {
      return count($route->tokenized_url) == count($this->tokenized_url);
    });
  }

  private function filter_routes_by_tokens(Array $routes) : iterable {
    $this->route_matched = false;
    return array_filter($routes, function($route) {
      for ($i=0; $i < count($this->tokenized_url); $i++) {
        // if this is a parameter check that its not empty
        if (preg_match("/{(.*)}/", $route->tokenized_url[$i], $tokens) && !empty($this->tokenized_url[$i])) {
          $route->route_parameters[$i][$tokens[1]] = $this->tokenized_url[$i];
        }else if ($route->tokenized_url[$i] == $this->tokenized_url[$i]) {
          // noop: keep checking tokenizd array
        } else {
          return false;
        }
      }

      // we are going to take the first matched route
      // this is dictates that the first route defined is used
      // this helps prioritize named routes over parameterized routes
      // ie /path/one/two > /path/one/{id} if it is defined before it
      if (!$this->route_matched) {
        $this->route_matched = true;
        return true;
      }else {
        return false;
      }
    });
  }

  private function extract_route_parameters(Route $route) : iterable {
    $parameters = [];
    for ($i=0; $i < count($this->tokenized_url); $i++) {
      if (preg_match("/{(.*)}/", $route->tokenized_url[$i], $tokens) && !empty($this->tokenized_url[$i])) {
        $parameters[] = $this->tokenized_url[$i];
      }
    }
    return $parameters;
  }

  private function extract_request_body() {
    return json_decode(file_get_contents('php://input'));
  }

  public function start() {
    $routes = $this->get_routes_by_method();
    $routes = $this->filter_routes_by_tokenized_url_length($routes);
    $routes = array_values($this->filter_routes_by_tokens($routes));
    $route = end($routes);

    if (!$route) {
      $this->noRouteFound();
      return;
    }

    $parameters = $this->extract_route_parameters($route);

    $func = $route->function;
    $func($this->request, ...$parameters);
  }

  public function list_routes(){
    return $this->routes;
  }

  private function add_route(Route $route){
    $this->routes[] = $route;
  }

  public function get(String $path, String $function){
    $this->add_route(
      new Route("GET", $path, $function)
    );
  }

  public function put(String $path, String $function){
    $this->add_route(
      new Route("PUT", $path, $function)
    );
  }

  public function post(String $path, String $function){
    $this->add_route(
      new Route("POST", $path, $function)
    );
  }

  public function delete(String $path, String $function){
    $this->add_route(
      new Route("DELETE", $path, $function)
    );
  }

}