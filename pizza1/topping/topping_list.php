<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Topping List</h1>

	<h2>Toppings</h2>
        <table>
            <tr>
                <th>Topping Name</th>
                <th>&nbsp;</th>
            </tr>
	<!-- new -->    
	
	    <?php foreach ($toppings as $topping) : ?>
	    <tr>
		<td><?php echo $topping['topping']; ?></td>
	   	<td><form action="." method="post">
		    <input type="hidden" name="action"
			   value="delete_topping">
		   <input type="hidden" name="topping_name"
			  value="<?php echo $topping['topping']; ?>">
		   <input type="submit" value="Delete">
		  </form></td>
	    </tr>
	   <?php endforeach; ?>	
        </table>
	<p class="last_paragraph">
	   <a href="?action=show_add_form">Add Topping</a>
    </section>
</main>
<?php include '../view/footer.php'; 
