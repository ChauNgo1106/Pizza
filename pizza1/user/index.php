<?php

require('../model/database.php');
require('../model/user_db.php');

$action = filter_input(INPUT_POST, 'action');

if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_users';
    }
} if ($action == 'list_users') {
    try {
        $usernames = get_users($db);
        include('user_list.php');
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('../errors/database_error.php');
    }
} else if ($action == 'delete_user') {
	$usernames = filter_input(INPUT_POST, 'name'); 
	delete_user($db, $usernames);
   	header("Location: .");
} else if ($action == 'show_add_form') {
	 include('user_add.php');
} else if ($action == 'add_user') {
    $usernames = filter_input(INPUT_POST, 'username');
    if ($usernames == NULL || $usernames == FALSE) {
        $error = "Invalid username";
        include('../errors/error.php');
    }
    $room = filter_input(INPUT_POST, 'room',
			FILTER_VALIDATE_INT);

     try {
          add_user($db, $usernames, $room);
       } catch (PDOException $e) {
            $error_message = $e->getMessage();
            include('../errors/database_error.php');
            exit();  // needed here to avoid redirection of next line
	}
	header("Location: ."); //redirect back to View User List
}
?>
