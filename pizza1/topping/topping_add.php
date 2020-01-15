<?php include '../view/header.php';?>
<main>
	<h1>Add Topping</h1>
	<form action="index.php" method="post" id="add_topping_form">
	<input type="hidden" name="action" value="add_topping">

	<label>Topping Name:</label>
	<input type = "text" name="topping" />
	<br>
	
	<label>Does it contain meat?</label>
	<input type="radio" name="contain?" value="meat" checked>Has Meat
	<input type="radio" name="contain?" value="meatless">Meatless
	<br>
	
	<label>&nbsp;</label>
	<input type="submit" value="Add" />
	<br>
	
</form>
<p>
	<a href="index.php?action=list_toppings">View Topping List</a>
</p>
</main>
<?php include '../view/footer.php'; ?>

