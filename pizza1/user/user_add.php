<?php include '../view/header.php';?>
<main>
	<h1>Add User </h1>
	<form action="index.php" method="post" id="add_user_form">
	<input type="hidden" name="action" value="add_user">

	<label>Username:</label>
	<input type = "text" name="username" />
	<br>
	
	<label>Room</label>
	<select name="room">
	
	<option value="1">1</option>  
	<option value="2">2</option>  
	<option value="3">3</option>  
	<option value="4">4</option>  
	<option value="5">5</option>  
	<option value="6">6</option>  
	<option value="7">7</option>  
	<option value="8">8</option>  
	<option value="9">9</option>  
	<option value="10">10</option> 
	</select>
	<br>
	
	<label>&nbsp;</label>
	<input type="submit" value="Add User" />
	<br>
	
</form>
<p>
	<a href="index.php?action=list_users">View User List</a>
</p>
</main>
<?php include '../view/footer.php'; ?>

