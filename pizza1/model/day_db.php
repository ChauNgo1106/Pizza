<?php

function get_orders($db) {
    $query = 'SELECT pizza_orders.id as id, username, status FROM pizza_orders, shop_users
    			WHERE shop_users.id = pizza_orders.user_id';
    $statement = $db->prepare($query);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor();
    return $orders;    
}

function get_day($db) {
    $query = 'SELECT * from pizza_sys_tab' ;
    $statement = $db->prepare($query);
    $statement->execute();
    $current_day = $statement->fetchAll();
    $statement->closeCursor();
    return $current_day;    
}

?>
