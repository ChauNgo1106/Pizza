<?php include '../view/header.php'; ?>
<main>
	<form action="index.php" method="post" id="list_orders">
      	<input type="hidden" name="action" value="change_to_baked">
    <section>
        <h1>Current Orders Report</h1>
        <h2>Orders Baked but not delivered</h2>
        
         <?php foreach($orders as $order) : ?>
         	<?php 
         		if ($order['status'] == 'Baked') {
				 	echo "ID:" . $order['id'] . " " . "User " . $order['username'] . "<br>";
				} ?>
			<?php endforeach; ?>
			
        <h2>Orders Preparing (in the oven): Any ready now?</h2>
        
       <?php foreach($orders as $order) : ?>
         	<?php 
         		if ($order['status'] == 'Preparing') {
				 echo "ID:" . $order['id'] . " " . "User " . $order['username'] . "<br>";
				   }?>
			<?php endforeach; ?>
			
	<label>&nbsp;</label>
	<input type="submit" value="Mark Oldest Pizza Baked"/> 
        <!--Button for marking oldest preparing pizza as baked -->
        <br>  
    </section>
 </form>
</main>
<?php include '../view/footer.php'; 
