<?php

require('../model/database.php');
require('../model/initial.php');
require('../model/day_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}
if ($action == 'initial_db') {
    try {
        initial_db($db);
        header("Location: .");
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include ('../errors/database_error.php');
        exit();
    }
} else if ($action == 'next_day'){
	$current_day=get_day($db);
	$orders = get_orders($db);
	 header("Location: .");
} 

$current_day=get_day($db);
$orders = get_orders($db);
include 'day_list.php';



?>