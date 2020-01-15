<?php include '../view/header.php'; ?>
<main>
    <section>
    	<?php foreach ($current_day as $day) : ?>
        <h1>Today is day <?php echo $day['current_day']; ?></h1>
        
        <!-- for testability, please do not change these two buttons -->
        <form action="index.php" method="post">
            <input type="hidden" name="action" value="next_day">
            <input type="submit" value="Change to day <?php echo $day['current_day'] + 1; ?>" />
        </form>
		<?php endforeach; ?>
        <form  action="index.php" method="post">
            <input type="hidden" name="action" value="initial_db">           
            <input type="submit" value="Initialize DB (making day = 1)" />
            <br>
        </form>      
        <br>
        <h2>Today's Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>User</th>
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
    </section>
</main>
<?php include '../view/footer.php'; 
