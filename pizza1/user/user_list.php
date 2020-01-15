<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>User List</h1>
        <table>
            <tr>
                <th>Username</th>
                <th>Room</th>
                <th>&nbsp;</th>
            </tr>
	    <?php foreach ($usernames as $name) : ?>
	    <tr>
		<td><?php echo $name['username']; ?></td>
		<td><?php echo $name['room']; ?></td>
	   	<td><form action="." method="post">
		    <input type="hidden" name="action"
			   value="delete_user">
		   <input type="hidden" name="name"
			  value="<?php echo $name['username']; ?>">
		   <input type="submit" value="Delete">
		  </form></td>
	    </tr>
	   <?php endforeach; ?>	
        </table>
	<p class="last_paragraph">
	   <a href="?action=show_add_form">Add User</a>
    </section>
</main>
<?php include '../view/footer.php'; ?>
