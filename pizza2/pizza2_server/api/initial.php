<?php

// redirect log entries to local file, so you can read them on topcat
// create writable file php_server_errors.log in the project base directory
function set_local_error_log() {
    $dirs = explode(DIRECTORY_SEPARATOR, __DIR__);
    array_pop($dirs); // remove last element (this directory name)
    $project_root = implode('/', $dirs) . '/';
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', '0'); // displayed errors would mess up response
    ini_set('log_errors', 1);
// the following file needs to exist, be accessible to apache
// and writable (chmod 777 php_server_errors.log on Linux/Mac,
//  not needed on Windows)
    ini_set('error_log', $project_root . 'php_server_errors.log');
}

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
    
   	if (gethostname() === 'topcat') {
   	  
        // alter table for auto-incremente doesn't work on mysql v 5.6
        // so drop and recreate tables related with FK
        // so that first order will have orderid 1, etc.
        $queries[] = 'drop table order_topping';
        $queries[] = 'drop table pizza_orders';
        $queries[] = 'create table pizza_orders(
id integer auto_increment,
user_id integer,
size varchar(30) not null,
day integer not null,
status varchar(10),
foreign key (user_id) references shop_users(id),
foreign key (status) references status_values(status_value),
primary key(id)
);';
        $queries[] = 'create table order_topping (
order_id integer not null,
topping varchar(30) not null,
primary key (order_id, topping),
foreign key (order_id) references pizza_orders(id));';
    } else {
        // MariaDB on XAMPP can do reset of auto-inc (with just alter priv)
        // Reset auto_increment on table pizza_orders so first order will have id 1
      //  $queries[] = "ALTER TABLE pizza_orders AUTO_INCREMENT = 0;";
    }
    
    $queries[] = 'insert into pizza_sys_tab values (1);';
    $queries[] = "insert into menu_toppings values (1,'Pepperoni', 1);";
    $queries[] = "insert into menu_toppings values (2,'Onions', 0);";
    $queries[] = "insert into menu_sizes values (1,'Small', 12);";
    $queries[] = "insert into menu_sizes values (2,'Large', 16);";
    $queries[] = "insert into shop_users values (1,'joe', 6);";
    $queries[] = "insert into shop_users values (2,'sue', 3);";

    foreach ($queries as $q) {
        error_log("doing $q");
        $statement = $db->prepare($q);
        $statement->execute();
        $statement->closeCursor();
    }
}
