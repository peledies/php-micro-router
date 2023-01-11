<?php

require(__DIR__."/class.router.php");

$router = new Router();

$router->get('/api/example','example_one');
$router->get('/api/example/{id}','example_two');

$router->get('/api/example/thing','example_three');
$router->get('/api/example/{id}','example_two');
$router->get('/api/example/{id}/two/{id}','example_four');

$router->put('/api/example/{id}','example_five');
$router->post('/api/example/{id}','example_six');
$router->delete('/api/example/{id}','example_seven');

function example_one() {
  echo "[GET] Example One" . PHP_EOL;
}

function example_two(Request $request, $one) {
  echo "[GET] Example Two" . PHP_EOL;
  echo $one . PHP_EOL;
}

function example_three() {
  echo "[GET] Example Three" . PHP_EOL;
}

function example_four(Request $request, $one, $two) {
  echo "[GET] Example Four" . PHP_EOL;
  echo $one . PHP_EOL;
  echo $two . PHP_EOL;
}

function example_five(Request $request, $one) {
  echo "[PUT] Example Five" . PHP_EOL;
  echo $one . PHP_EOL;
}

function example_six(Request $request, $one) {
  echo "[POST] Example Six" . PHP_EOL;
  echo $one . PHP_EOL;
  print_R($request->body);
}

function example_seven(Request $request, $one) {
  echo "[DELETE] Example Seven" . PHP_EOL;
  echo $one . PHP_EOL;
}

$router->start();
