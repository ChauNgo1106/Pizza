<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Welcome Student!</h1>
        		<form action="index.php" method="post" >
        		<input type="hidden" name="action" value="show_status">
        <h2>Available Sizes</h2>
        		<?php foreach($sizes as $size) : ?> 
		    	<?php echo $size['size']; ?>  
		<?php endforeach; ?>

		<h2>Available Toppings</h2>
			<?php foreach($toppings as $topping) : ?>
	  	   	  <?php echo $topping['topping']; ?>
			<?php endforeach; ?>

        <p>Username:
			<select name="username?">
			<option selected="selected">Choose one</option>
				<?php
		    		foreach($usernames as $username) : ?>
		  	<option value="<?php echo $username['username']; ?>">
		     	<?php echo $username['username']; ?>
		  	</option>
					<?php endforeach; ?>
			</select>
		
	<label>&nbsp;</label>
		<input type="submit" value="Select your username"/>
       	 	
        
		<table>
            <tr>
            	<th>Order Number</th>
                <th>Username</th>
                <th>Status</th>
               
            </tr>
	    	<?php foreach ($orders as $order) : ?>
	    	<tr>
	    		<td><?php echo $order['id']; ?></td>
				<td><?php echo $order['username']; ?></td>
				<td><?php echo $order['status']; ?></td>
	    	</tr>
	   		<?php endforeach; ?>	
        </table>
        <h2> Button to acknowledge receipt of pizzas that are Baked
            (if there are any Baked ones) </h2>
	<label>&nbsp;</label>
		<input type="submit" value="Receipt"/>
       	 	
	<p class="last_paragraph">
	  <a href="?action=show_order_form">Order Pizza</a>
    </section>
</main>
<?php include '../view/footer.php'; 
