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
	 if ($body_params->topping !== NULL && $body_params->topping->name !==null) {
	 	global $mysqli;
	 	
	 	 $result = $mysqli->query("SELECT * FROM topping WHERE name = '" . $body_params->topping->name . "'");

 		 if ($result->num_rows > 0) {
 		 	 echo "Topping already exists.";
 		 } else {
 		 	$sql = "INSERT INTO topping (name) VALUES ('". $body_params->topping->name ."')";

			 if ($mysqli->query($sql) === TRUE) {
				 echo "New record created successfully";
			 } else {
				 echo "Error: " . $sql . "<br>" . $mysqli->error;
			 }
 		 }
	 	$mysqli->close();
	 } else {
	 	echo "no topping to add";
	 }
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
	 
	 if ($body_params->topping_id !== NULL) {
	 	
	 	 // all this crap because I can't access the pizza_id from $app 	
		 $arr = explode("pizzas/" ,$app->request->getPath());
		 $arr2 = explode("/toppings" ,$arr[1]);
		 $pizza_id = $arr2[0];
	 	 $topping_id = $body_params->topping_id;
	 	
	 	 global $mysqli;

		 $result = $mysqli->query("SELECT * FROM topping_on_pizza WHERE pizzaId = " . $pizza_id . " && toppingId = " . $topping_id);
		 
		 if ($result->num_rows > 0) {
		 	echo "that topping is already on pizza";
		 } else {
		 	
		 	$sql = "INSERT INTO topping_on_pizza (pizzaId, toppingId) VALUES (". $pizza_id .",". $topping_id .")";

			 if ($mysqli->query($sql) === TRUE) {
				 echo "New record created successfully";
			 } else {
				 echo "Error: " . $sql . "<br>" . $mysqli->error;
			 }
		 }
		 
		 $mysqli->close();
	 } else {
	 	echo "topping not added to pizza";
	 }
});

/****** Run App  *******/
$app->run();
?>
