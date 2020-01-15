<?php
require('../model/database.php');
require('../model/order_db.php');
require('../model/user_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_orders';
    }
}
if ($action == 'list_orders'){
	$orders = get_orders($db);
   include('order_list.php');

} else if ($action == 'change_to_baked'){
	change_to_baked($db);
	header("Location: .");
	
}
?>