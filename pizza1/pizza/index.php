<?php
require('../model/database.php');
require('../model/user_db.php');
require('../model/topping_db.php');
require('../model/size_db.php');
require('../model/order_db.php');

$action = filter_input(INPUT_POST, 'action');

if ($action == NULL){
	$action = filter_input(INPUT_GET, 'action');
	if ($action == NULL){
	   $action = 'welcome_student';
	}

}
if ($action == 'welcome_student'){
  try{
	//must be as same as return varible in *_db.php
	$toppings = get_toppings($db);
	$sizes = get_sizes($db);
	$usernames= get_users($db);
	//make welcome_student.php happy.
	$orders = get_orders_by_name($db, $username);
	include('student_welcome.php');
  } catch(PDOException $e) {
	$error_message = $e->getMessage();
	include('../errors/database_error.php');
  }
} else if ($action == 'show_status') {
	$username = filter_input(INPUT_POST, 'username?');
	
	$toppings = get_toppings($db);
	$sizes = get_sizes($db);
	$usernames= get_users($db);
	$orders = get_orders_by_name($db, $username);
	
	include('student_welcome.php');
} else if ($action == 'show_order_form'){
	
	//$toppings = get_toppings($db);
	$sizes = get_sizes($db);
	$usernames= get_users($db);
	$meat = get_toppings_meat($db);  
	$meatless = get_toppings_meatless($db);  
	
	include('order_pizza.php');
} else if($action == 'order_pizza') {
	
	$username = filter_input(INPUT_POST, 'username?');
	if ($username == NULL || $username == FALSE) {
		$error = "Invalid username";
		include('../errors/error.php');
	}
	

	$size = filter_input(INPUT_POST, 'size?');
    	if ($size == NULL || $size == FALSE) {
        	$error = "Invalid size";
        	include('../errors/error.php');
    	}
	
try {

	add_order($db, $username , $size );
	
  } catch(PDOException $e) {
	$error_message = $e->getMessage();
	include('../errors/database_error.php');
  }
	header("Location: .");
}

