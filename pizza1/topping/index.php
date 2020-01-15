<?php

require('../model/database.php');
require('../model/topping_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_toppings';
    }
}

if ($action == 'list_toppings') {
    try {
        $toppings = get_toppings($db);
        include('topping_list.php');
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('../errors/database_error.php');
    }
} else if ($action == 'delete_topping') {
	$topping_name = filter_input(INPUT_POST, 'topping_name'); 
	delete_topping($db, $topping_name);
	//redirect back to list_topping page
   	header("Location: .");
} else if ($action == 'show_add_form') {
	 include('topping_add.php');
} else if ($action == 'add_topping') {
    $topping_name = filter_input(INPUT_POST, 'topping');
    if ($topping_name == NULL || $topping_name == FALSE) {
        $error = "Invalid topping name";
        include('../errors/error.php');
    } else {
        $is_meat_reply = filter_input(INPUT_POST, 'contain?');
        if ($is_meat_reply === "meat") {
            $is_meat = 1;
        } else if ($is_meat_reply === "meatless") {
            $is_meat = 0;
        } else {
            $error = "Invalid meat/meatless reply: " . $is_meat_reply;
            include('../errors/error.php');
            exit();
        }
        try {
            add_topping($db, $topping_name, $is_meat);
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            include('../errors/database_error.php');
            exit();  // needed here to avoid redirection of next line
        }
        // Redirect back to index.php (see pp. 164-165)
        // (don't include index.php inside index.php)
        header("Location: .");
    }
}
