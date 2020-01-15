<?php
// the try/catch for these actions is in the caller
function add_topping($db, $topping_name, $isMeat)  
{
$query = 'INSERT INTO menu_toppings
	(topping, is_meat)
	VALUES (:topping_name , :isMeat)';
$statement = $db->prepare($query);
$statement->bindValue(':topping_name', $topping_name);
$statement->bindValue(':isMeat', $isMeat);
$statement->execute();
$statement->closeCursor();
}

function delete_topping($db, $topping_name)
{
	$query = 'DELETE FROM menu_toppings 
		  WHERE topping = :topping_name';
	$statement = $db->prepare($query);
	$statement->bindValue(':topping_name', $topping_name);
	$statement->execute();
	$statement->closeCursor();
}

function get_toppings($db) {
    $query = 'SELECT * FROM menu_toppings ORDER BY topping DESC';
    $statement = $db->prepare($query);
    $statement->execute();
    $toppings = $statement->fetchAll();
    $statement->closeCursor();
    return $toppings;    
}

function get_toppings_meat($db) {
    $query = 'SELECT * FROM menu_toppings
    			WHERE is_meat = 1';
    $statement = $db->prepare($query);
    $statement->execute();
    $meat = $statement->fetchAll();
    $statement->closeCursor();
    return $meat;    
}

function get_toppings_meatless($db) {
    $query = 'SELECT * FROM menu_toppings
    			WHERE is_meat = 0';
    $statement = $db->prepare($query);
    $statement->execute();
    $meatless = $statement->fetchAll();
    $statement->closeCursor();
    return $meatless;    
}

?>

