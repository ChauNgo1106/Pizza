<?php
function get_user_id($db, $username) {
   
    $query = 'SELECT shop_users.id FROM shop_users
              WHERE shop_users.username = :username';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $user_id = $statement->fetch();
    $statement->closeCursor();
    return $user_id;
}

function add_order($db, $name , $size) {

$status = "Preparing";
$day = 1;

$id = get_user_id($db, $name);
$i = $id['id'];

$query = 'INSERT INTO pizza_orders
			(user_id, size ,day , status)
			VALUES (:i, :size, :day , :status)';
$statement = $db->prepare($query);
$statement->bindValue(':i', $i);
$statement->bindValue(':size', $size);
$statement->bindValue(':day', $day);
$statement->bindValue(':status', $status);
$statement->execute();
$statement->closeCursor();

}
function change_to_baked($db) {
    $query = 'UPDATE pizza_orders
    		  SET status = "Baked"
    		  WHERE id = 
    		  			(SELECT MIN(id) FROM pizza_orders
    					WHERE status = "Preparing" ) ';
    $statement = $db->prepare($query);
   	$statement->bindValue(':min_id', $min_id);
    $statement->execute();
    $statement->closeCursor();   
}
function get_orders($db) {
    $query = 'SELECT pizza_orders.id as id, username, status FROM pizza_orders, shop_users
    			WHERE shop_users.id = pizza_orders.user_id';
    $statement = $db->prepare($query);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor();
    return $orders;    
}
function get_orders_by_name($db, $username) {
    $query = 'SELECT pizza_orders.id as id, username, status FROM pizza_orders, shop_users
    			WHERE shop_users.username = :username and shop_users.id = pizza_orders.user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor();
    return $orders;    
}
?>

