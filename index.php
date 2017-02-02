<?php
define( '_ROOT', dirname(__FILE__) . '');
require_once("models/config.php");
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

//$app->config('debug', false);
//$app->view(new \Slim\jsonAPI\JsonApiView());
$app->get('/pizzas', function () use ($app){

	global $mysqli;

	$result = $mysqli->query("SELECT * FROM pizza");

	if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Pizza: " . $row["name"]. " " . $row["description"]. "<br>";
    }
	}

	$mysqli->close();
});

$app->post('/pizzas', function () use ($app){
	 $body_params = json_decode($app->request->getBody());
	 
	 if ($body_params->pizza !== NULL && $body_params->pizza->name !==null) {
	 	global $mysqli;
	 	
	 	 $result = $mysqli->query("SELECT * FROM pizza WHERE name = '" . $body_params->pizza->name . "'");

 		 if ($result->num_rows > 0) {
 		 	 echo "Pizza already exists.";
 		 } else {
 		 	$sql = "INSERT INTO pizza (name, description) VALUES ('". $body_params->pizza->name ."', '" . $body_params->pizza->description . "')";

			 if ($mysqli->query($sql) === TRUE) {
				 echo "New record created successfully";
			 } else {
				 echo "Error: " . $sql . "<br>" . $mysqli->error;
			 }
 		 }
	 	$mysqli->close();
	 } else {
	 	echo "no pizza created";
	 }
	
});

$app->get('/toppings', function () use ($app){
	global $mysqli;

 $result = $mysqli->query("SELECT * FROM topping");

 if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
				echo "id: " . $row["id"]. " - Topping: " . $row["name"]. "<br>";
		}
 }

 $mysqli->close();
});

$app->post('/toppings', function () use ($app){
	 $body_params = json_decode($app->request->getBody());
	 echo "toppings post";
});

$app->get('/pizzas/:pizza_id/toppings', function ($pizza_id) {
		
	global $mysqli;

	$result = $mysqli->query("SELECT * FROM topping_on_pizza WHERE pizzaId = " . $pizza_id);

	if ($result->num_rows > 0) {
		// output data of each row
		echo "pizza id " . $pizza_id . " has these toppings<br>";
		while($row = $result->fetch_assoc()) {
			echo "topping id: " . $row["toppingId"]. "<br>";
		}
	}

	$mysqli->close();
		
});

$app->post('/pizzas/:pizza_id/toppings', function () use ($app){

	 $body_params = json_decode($app->request->getBody());

	 // all this crap because I can't access the pizza_id from $app 	
	 $arr = explode("pizzas/" ,$app->request->getPath());
	 $arr2 = explode("/toppings" ,$arr[1]);
	 $pizza_id = $arr2[0];
	 
	 $name = $body_params->topping_id->name;
	 echo "pizza id, $pizza_id and $name";
});

/****** Run App  *******/
$app->run();
?>
