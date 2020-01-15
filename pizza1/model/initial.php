<?php

// reestablish initial database contents
// Don't change this, or put it back this way for delivery

function initial_db($db) {
    $queries = array();
    $queries[] = 'delete from order_topping;';
    $queries[] = 'delete from pizza_orders;';
    $queries[] = 'delete from menu_sizes;';
    $queries[] = 'delete from menu_toppings;';
    $queries[] = 'delete from shop_users;';
    $queries[] = 'delete from pizza_sys_tab;';
    $queries[] = 'insert into pizza_sys_tab values (1);';
    $queries[] = "insert into menu_toppings values (1,'Pepperoni', 1);";
    $queries[] = "insert into menu_toppings values (2,'Onions', 0);";
    $queries[] = "insert into menu_sizes values (1,'Small', 12);";
    $queries[] = "insert into menu_sizes values (2,'Large', 16);";
    $queries[] = "insert into shop_users values (1,'joe', 6);";
    $queries[] = "insert into shop_users values (2,'sue', 3);";

    foreach ($queries as $q) {
        $statement = $db->prepare($q);
        $statement->execute();
    }
}
