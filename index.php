<?php
define( '_ROOT', dirname(__FILE__) . '');
//require_once("models/config.php");
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

//$app->config('debug', false);
//$app->view(new \Slim\jsonAPI\JsonApiView());
$app->get('/pizzas', function () use ($app){
	 echo "pizzas get";
});

$app->post('/pizzas', function () use ($app){
	 $body_params = json_decode($app->request->getBody());
	 echo "pizzas post";
});

$app->get('/toppings', function () use ($app){
	 echo "toppings get";
});

$app->post('/toppings', function () use ($app){
	 $body_params = json_decode($app->request->getBody());
	 echo "toppings post";
});

$app->get('/pizzas/:pizza_id/toppings', function ($pizza_id) {
		echo "pizza id, $pizza_id";
});

$app->post('/pizzas/:pizza_id/toppings', function () use ($app){

	 $body_params = json_decode($app->request->getBody());

	 $arr = explode("pizzas/" ,$app->request->getPath());
	 $arr2 = explode("/toppings" ,$arr[1]);
	 $pizza_id = $arr2[0];
	 $name = $body_params->topping_id->name;
	 echo "pizza id, $pizza_id and $name";
});

/****** Run App  *******/
$app->run();
?>
