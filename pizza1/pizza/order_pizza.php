<?php include '../view/header.php'; ?>
<main>
    <h1>Build Your Pizza!</h1>
       	   <form action="index.php" method="post" id="show_order_form">
      	     <input type="hidden" name="action" value="order_pizza">
	
	<h2>Pizza Size:</h2>
		<?php foreach($sizes as $size) : ?>
			<input type="radio" name="size?" value ="<?php echo $size['size']; ?>" checked>
				<?php echo $size['size']?>
		<?php endforeach; ?>
		</br>
	<h2>Toppings</h2>
	
		<label>Meat</label>
		</br>
  			<?php foreach($meat as $Meat) : ?>
  			
		  	<input type="checkbox" name="meat?" value="<?php echo $meat['is_meat']?>">
			<?php
					echo $Meat['topping']; 
			?>
			</br>
		<?php endforeach; ?>
	
		
		<label>Meatless</label>
		</br>
  			<?php foreach($meatless as $Meatless) : ?>
  			
		  	<input type="checkbox" name="meat?" value="<?php echo $meatless['is_meat']?>">
			<?php
					echo $Meatless['topping']; 
			?>
			</br>
		<?php endforeach; ?>
		
	</br>
    <label>Username:</label>
		<select name="username?">
		<option value="selected" selected>Choose one</option>
		<?php
		    foreach($usernames as $username) : ?>
		  <option value="<?php echo $username['username']; ?>">
		    <?php echo $username['username']; ?></option>
		<?php endforeach; ?>
		</select>
	</br>
	
	<label>&nbsp;</label>
  	<input type="submit" value="Order Pizza"/> 
  	
 </form>    	
</main>
<?php include '../view/footer.php'; 
