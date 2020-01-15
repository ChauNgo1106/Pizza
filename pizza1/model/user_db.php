<?php
// the try/catch for these actions is in the caller

function add_user($db, $name, $room_num)  
{
	$query = 'INSERT INTO shop_users
		(username, room)
		VALUES (:name, :room_num)';
	$statement = $db->prepare($query);
	$statement->bindValue(':name', $name);
	$statement->bindValue(':room_num', $room_num);
	$statement->execute();
	$statement->closeCursor();
}

function delete_user($db, $name)
{
	$query = 'DELETE FROM shop_users 
		  WHERE username = :name';
	$statement = $db->prepare($query);
	$statement->bindValue(':name', $name);
	$statement->execute();
	$statement->closeCursor();
}

function get_users($db) {
    $query = 'SELECT * FROM shop_users ORDER BY username ASC';
    $statement = $db->prepare($query);
    $statement->execute();
    $usernames = $statement->fetchAll();
    $statement->closeCursor();
    return $usernames;    
}
?>

