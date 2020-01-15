<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Welcome Student to the PHP client!</h1>
        <h2>Available Sizes</h2>
        <ul>
            <?php foreach ($sizes as $size) : ?>
                <li class="horizontal">
                    <?php echo $size['size']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <h2>Available Toppings</h2>
        <ul>
            <?php foreach ($toppings as $topping) : ?>
                <li class="horizontal">
                    <?php echo $topping['topping']; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <form  action="index.php" method="post">
            <input type="hidden" name="action" value="set_user">
            <label>Username:</label>
            <select name="user_id" required="required">
                      <?php foreach ($users as $user) : ?>
                    <option   <?php
                    if ($user_id == $user['id']) {
                        echo 'selected = "selected"';
                        $username = $user['username'];
                    }
                    ?> 
                        value="<?php echo $user['id']; ?>" > <?php echo $user['username']; ?>
                    </option>
                <?php endforeach; ?> 
            </select>
            <input type="submit" value="Select Your Username" /> <br><br>
        </form>

        <?php
        if (!empty($user_id) ):
            
        if (count($user_preparing_orders) + count($user_baked_orders) == 0):
            echo 'No orders in progress for this user';
        else:
            ?>
            <h2>Orders in progress for user <?php echo $username ?></h2>

            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Size</th>
                    <th>Toppings</th>
                    <th>Status</th>

                </tr>
                <?php foreach ($user_baked_orders as $order) : ?>
             <tr>
                        <td><?php echo $order['id']; ?> </td>
                        <td><?php echo $order['size']; ?> </td> 
                        <td><?php
                            $toppings = $order['toppings'];
                            foreach ($toppings as $t) {
                                echo $t . ' ';
                            }
                            ?></td> 
                        <td><?php echo 'Baked'; ?> </td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach ($user_preparing_orders as $order) : ?>
                    <tr>
                        <td><?php echo $order['id']; ?> </td>
                        <td><?php echo $order['size']; ?> </td> 
                        <td><?php
                            $toppings = $order['toppings'];
                            foreach ($toppings as $t) {
                                echo $t . ' ';
                            }
                            ?></td> 
                        <td><?php echo 'Preparing'; ?> </td>
                    </tr>
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>
      
        <?php if (count($user_baked_orders) > 0): ?>
            <form action="index.php" method="post">
                <input type="hidden" name="user_id"
                       value="<?php echo $user_id; ?>">
                <input type="hidden" name="action"
                       value="update_order_status">
                <input type="submit" value="Acknowledge Delivery of Baked Pizzas">
            </form>
        <?php endif; ?>
        <?php endif; ?>
        <p>
            <a href="?action=order_pizza&amp;user_id=<?php echo $user_id; ?>"><strong>Order Pizza</strong></a>
        </p>
    </section>
</main>
<?php include '../view/footer.php'; 