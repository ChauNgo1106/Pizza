<!-- Note from eoneil: 
   Note that it isn't good programming practice to have such specific 
   strings as 'cs637/eoneil' embedded in code, but PHP doesn't make it
   easy to do better. The "app_path" is location of the project relative 
   to the web server's document root.
-->
<!-- CHANGE xxxxx to your own topcat username-->
<?php $app_path = "/cs637/xxxxx/pizza2_phpclient/"; ?>
<!DOCTYPE html>
<html> 
    <head>
        <title>My Pizza Shop</title>
        <link rel="stylesheet" type="text/css"
              href="<?php echo $app_path . 'main.css' ?>">
        <!-- needed for mobile devs: see https://www.w3schools.com/css/css_rwd_viewport.asp -->
        <meta name="viewport" content = "width=device-width">
        <link rel="shortcut icon" href="images/pizzapie.ico" type="image/x-icon"/>
    </head>
    <body>
        <header>
            <img id="pizzapie" src="<?php echo $app_path ?>images/pizzapie.jpg" alt="Pizza">
            <h1>My Pizza Shop</h1>
            <br> <!-- does clear too -->
            <nav>
                <ul>
                    <li><a href="<?php echo $app_path ?>">Home</a></li>
                    <li><a href="<?php echo $app_path ?>pizza/">Student Orders</a></li>   
                </ul>
            </nav>
        </header>
