<?php
require __DIR__ . '/../vendor/autoload.php';
require 'initial.php';
// provide aliases for long classname--
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

set_local_error_log(); // redirect error_log to ../php_server_errors.log
// Instantiate the app
$app = new \Slim\App();
// Add middleware that can add CORS headers to response (if uncommented)
// These CORS headers allow any client to use this service (the wildcard star)
// We don't need CORS for the ch05_gs client-server project, because
// its network requests don't come from the browser. Only requests that
// come from the browser need these headers in the response to satisfy
// the browser that all is well. Even in that case, the headers are not
// needed unless the server for the REST requests is different than
// the server for the HTML and JS. When we program in Javascript we do
// send requests from the browser, and then the server may need to
// generate these headers.
// Also specify JSON content-type, and overcome default Allow of GET, PUT
// Note these will be added on failing cases as well as sucessful ones
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                    ->withHeader('Content-Type', 'application/json')
                    ->withHeader('Allow', 'GET, POST, PUT, DELETE');
});
// Turn PHP errors and warnings (div by 0 is a warning!) into exceptions--
// From https://stackoverflow.com/questions/1241728/can-i-try-catch-a-warning
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // error was suppressed with the @-operator--
    // echo 'in error handler...';
    if (0 === error_reporting()) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Slim has default error handling, but not super useful
// so we'll override those handlers so we can handle errors 
// in this code, and report file and line number.
// This also means we don't set $config['displayErrorDetails'] = true;
// because that just affects the default error handler.
// See https://akrabat.com/overriding-slim-3s-error-handling/
// To see this in action, put a parse error in your code
$container = $app->getContainer();
$container['errorHandler'] = function ($container) {
    return function (Request $request, Response $response, $exception) {
        // retrieve logger from $container here and log the error
        $response->getBody()->rewind();
        $errorJSON = '{"error":{"text":' . $exception->getMessage() .
                ', "line":' . $exception->getLine() .
                ', "file":' . $exception->getFile() . '}}';
        //     echo 'error JSON = '. $errorJSON;           
        error_log("server error: $errorJSON");
        return $response->withStatus(500)
                        //            ->withHeader('Content-Type', 'text/html')
                        ->write($errorJSON);
    };
};

// This function should not be called because errors are turned into exceptons
// but it still is, on error 'Call to undefined function' for example
$container['phpErrorHandler'] = function ($container) {
    return function (Request $request, Response $response, $error) {
        // retrieve logger from $container here and log the error
        $response->getBody()->rewind();
        echo 'PHP error:  ';
        print_r($error->getMessage());
        $errorJSON = '{"error":{"text":' . $error->getMessage() .
                ', "line":' . $error->getLine() .
                ', "file":' . $error->getFile() . '}}';
        error_log("server error: $errorJSON");
        return $response->withStatus(500)
                        //  ->withHeader('Content-Type', 'text/html)
                        ->write($errorJSON);
    };
};
$app->get('/day', 'getDay');
$app->get('/toppings/{id}', 'getToppings');
$app->get('/toppings', 'getAllToppings');
$app->get('/sizes', 'getAllSizes');
$app->get('/users', 'getAllUsers');
$app->get('/orders', 'getAllOrders');
$app->get('/orders/{id}', 'getOrders');
$app->get('/orderToppings' , 'getOrderToppings');
$app->post('/day', 'postDay');
$app->post('/orders', 'postOrder');
$app->put('/orders/{id}', 'putOrder');
$app->put('/order/{id}', 'updateOrderFinished');
// Take over response to URLs that don't match above rules, to avoid sending
// HTML back in these cases
$app->map(['GET', 'POST', 'PUT', 'DELETE'], '/{routes:.+}', function($req, $res) {
    $uri = $req->getUri();
    $errorJSON = '{"error": "HTTP 404 (URL not found) for URL ' . $uri . '"}';
    return $res->withStatus(404)
                    ->write($errorJSON);
});
$app->run();

// functions without try-catch are depending on overall
// exception handlers set up above, which generate HTTP 500
// Functions that need to generate HTTP 400s (client errors)
// have try-catch
// Function calls that don't throw return HTTP 200
function getDay(Request $request, Response $response) {
    error_log("server getDay");
    $sql = "select current_day FROM pizza_sys_tab";
    $db = getConnection();
    $stmt = $db->query($sql);
    // fetch just column 0 value--
    return $stmt->fetch(PDO::FETCH_COLUMN, 0);
}
//postday here
function postDay(Request $request, Response $response) {
    error_log("server postDay");
    $db = getConnection();
    initial_db($db);
    return "1";  // new day value
}
function getToppings(Request $request, Response $response, $arg){
	error_log("server getToppings");
	$top = $arg['id'];
	$db = getConnection();
	$query = "select * from menu_toppings where id = :id";
	$statement = $db->prepare($query);
	$statement->bindValue(":id", $top);
	$statement->execute();
	$toppings = $statement->fetch(PDO::FETCH_ASSOC);
	$statement->closeCursor();
	echo json_encode($toppings);
}
function getAllToppings (Request $request, Response $response){   
   // echo json_encode($categories);
	error_log("server getAllToppings");
	$db = getConnection();
	$query = "select * from menu_toppings";
	$statement = $db->prepare($query);
	$statement->execute();
	$toppings = $statement->fetchAll(PDO::FETCH_ASSOC);
	$statement->closeCursor();
	echo json_encode($toppings);
	
}
function getAllSizes (Request $request, Response $response){   
   // echo json_encode($categories);
	error_log("server getAllSizes");
	$db = getConnection();
	$query = "select * from menu_sizes";
	$statement = $db->prepare($query);
	$statement->execute();
	$sizes = $statement->fetchAll(PDO::FETCH_ASSOC);
	$statement->closeCursor();
	echo json_encode($sizes);
	
}
function getAllUsers (Request $request, Response $response){   
   // echo json_encode($categories);
	error_log("server getAllUsers");
	$db = getConnection();
	$query = "select * from shop_users";
	$statement = $db->prepare($query);
	$statement->execute();
	$users = $statement->fetchAll(PDO::FETCH_ASSOC);
	$statement->closeCursor();
	echo json_encode($users);
	
}
function getAllOrders (Request $request, Response $response){   
   // echo json_encode($categories);
	error_log("server getAllOrders");
	$db = getConnection();
	$query = "select * from pizza_orders";
	$statement = $db->prepare($query);
	$statement->execute();
	$orders = $statement->fetchAll(PDO::FETCH_ASSOC);
	$statement->closeCursor();
	echo json_encode($orders);
	
}
function getOrders(Request $request, Response $response, $arg){
	error_log("server getOrder");
	$ord = $arg['id'];
	$db = getConnection();
	$query = "select * from pizza_orders where id = :id";
	$statement = $db->prepare($query);
	$statement->bindValue(":id", $ord);
	$statement->execute();
	$orders = $statement->fetch(PDO::FETCH_ASSOC);
	$statement->closeCursor();
	if ($orders === FALSE) {
        // can't find order, so return not-found
        $errorJSON = '{"error":{"text":order not found}}';
        error_log("server error $errorJSON");
        return $response->withStatus(404) // client error
                        ->write($errorJSON);     
    } else {
	echo json_encode($orders);
	}
	
}
function getOrderToppings(Request $request, Response $response){
	error_log("server getOrderTopping");
	$db = getConnection();
	$query = "select * from order_topping";
	$statement = $db->prepare($query);
	$statement->execute();
	$orderToppings = $statement->fetchAll(PDO::FETCH_ASSOC);
	$statement->closeCursor();
	if ($orderToppings === FALSE) {
        $errorJSON = '{"error":{"text":order not found}}';
        error_log("server error $errorJSON");
        return $response->withStatus(404) // client error
                        ->write($errorJSON);     
    } else {
	echo json_encode($orderToppings);
	}
	
}
function postOrder(Request $request, Response $response) {
    error_log("server postOrder");
    error_log("server: body: " . $request->getBody());
    $order = $request->getParsedBody();  // Slim does JSON_decode here (convert JSON into array of order)
    									//including the topping occuring as an array
    error_log('server: parsed order = ' . print_r($order, true));
    if ($order == NULL) { // parse failed (bad JSON)
        $errorJSON = '{"error":{"text":"bad JSON in request"}}';
        error_log("server error $errorJSON");
        return $response->withStatus(400)  //client error
                        ->write($errorJSON);
    }
    try {
        $db = getConnection();
        $orderID = addOrder($db, $order['user_id'], $order['size'], $order['day'], $order['status']);
        
        //get toppings from order.
		$order_toppings = array();
		foreach($order['toppings'] as $topping){
			$order_toppings[] = $topping;
		}
		addToppingOrder($db, $orderID, $order_toppings);
		//end get toppings
    } catch (PDOException $e) {
        // if duplicate order, blame client--
        if (strstr($e->getMessage(), 'SQLSTATE[23000]')) {
            $errorJSON = '{"error":{"text":' . $e->getMessage() .
                    ', "line":' . $e->getLine() .
                    ', "file":' . $e->getFile() . '}}';
            error_log("server error $errorJSON");
            return $response->withStatus(400) // client error
                            ->write($errorJSON);
        } else {
            throw($e);  // generate HTTP 500 as usual         
        }
    }
    $order['id'] = $orderID;  // fix up id to current one
    $JSONcontent = json_encode($order); //array content into JSON
    $location = $request->getUri() . '/' . $order["id"];
   //return a success code 200
      return $response ->withStatus(200)
                    	->write("success code: 200\n");
}
function addOrder($db,$user_id, $size, $day, $status) {
    error_log("server addOrder");
    $query = 'INSERT INTO pizza_orders
                 ( user_id, size, day, status)
              VALUES
                 ( :user_id, :size, :day, :status)';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':size', $size);
    $statement->bindValue(':day', $day);
    $statement->bindValue(':status', $status);
    $statement->execute();
    $statement->closeCursor();
    $orderID = $db->lastInsertId();
    return $orderID;
}
//add topping of an order into order_topping table
function addToppingOrder($db, $orderID, $toppings){
	error_log("server addTopping");
	$toppingString = '';
	foreach ($toppings as $topping){
		$toppingString = $toppingString . ' ' . $topping;
	}
		$query = 'INSERT INTO order_topping
					(order_id, topping)
				VALUES 
					(:order_id, :topping)';
		$statement = $db->prepare($query);
    	$statement->bindValue(':order_id', $orderID);
    	$statement->bindValue(':topping', $toppingString);
    	$statement->execute();
    	$statement->closeCursor();
}
//update order to be Baked, mark finished if client acknowledges this order as well.
function putOrder(Request $request, Response $response) {
    error_log("server putOrder");
    error_log("server: body: " . $request->getBody());
    $order = $request->getParsedBody();  // Slim does JSON_decode here (convert JSON into array of order)
    									//including the topping occuring as an array
    error_log('server: parsed order = ' . print_r($order, true));
    if ($order == NULL) { // parse failed (bad JSON)
        $errorJSON = '{"error":{"text":"bad JSON in request"}}';
        error_log("server error $errorJSON");
        return $response->withStatus(400)  //client error
                        ->write($errorJSON);
    }
    try {
        $db = getConnection();
       	updateOrder($db, $order['id']);
    } catch (PDOException $e) {
        // if duplicate product, blame client--
        if (strstr($e->getMessage(), 'SQLSTATE[23000]')) {
            $errorJSON = '{"error":{"text":' . $e->getMessage() .
                    ', "line":' . $e->getLine() .
                    ', "file":' . $e->getFile() . '}}';
            error_log("server error $errorJSON");
            return $response->withStatus(400) // client error
                            ->write($errorJSON);
        } else {
            throw($e);  // generate HTTP 500 as usual         
        }
    }
    $JSONcontent = json_encode($order); //convert an array content into JSON
    return $response ->withStatus(200)
                    	->write("200");
}
//update function
function updateOrder($db, $id) {
    error_log("server updateOrder");
    $query = 'UPDATE pizza_orders
    		  SET pizza_orders.status = "Baked"
              WHERE id = :id' ;
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}
//mark finished if client acknowledges this order as well.
function updateOrderFinished(Request $request, Response $response) {
    error_log("server  updateOrderFinished");
    error_log("server: body: " . $request->getBody());
    $order = $request->getParsedBody();  // Slim does JSON_decode here (convert JSON into array of order)
    									//including the topping occuring as an array
    error_log('server: parsed order = ' . print_r($order, true));
    if ($order == NULL) { // parse failed (bad JSON)
        $errorJSON = '{"error":{"text":"bad JSON in request"}}';
        error_log("server error $errorJSON");
        return $response->withStatus(400)  //client error
                        ->write($errorJSON);
    }
    try {
        $db = getConnection();
       	updateOrder1($db, $order['id']);
    } catch (PDOException $e) {
        // if duplicate product, blame client--
        if (strstr($e->getMessage(), 'SQLSTATE[23000]')) {
            $errorJSON = '{"error":{"text":' . $e->getMessage() .
                    ', "line":' . $e->getLine() .
                    ', "file":' . $e->getFile() . '}}';
            error_log("server error $errorJSON");
            return $response->withStatus(400) // client error
                            ->write($errorJSON);
        } else {
            throw($e);  // generate HTTP 500 as usual         
        }
    }
    $JSONcontent = json_encode($order); //convert an array content into JSON
    return $response ->withStatus(200)
                    	->write("200");
}
//update function
function updateOrder1($db, $id) {
    error_log("server updateOrder");
    $query = 'UPDATE pizza_orders
    		  SET pizza_orders.status = "Finished"
              WHERE id = :id' ;
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}


// set up to execute on XAMPP or at topcat.cs.umb.edu:
// --set up a mysql user named pizza_user on your own system
// --see database/dev_setup.sql and database/createdb.sql
// --load your mysql database on topcat with the pizza db
// Then this code figures out which setup to use at runtime
function getConnection() {
    if (gethostname() === 'topcat') {
        $dbuser = 'chau1993';  // CHANGE THIS to your cs.umb.edu username
        $dbpass = 'chau1993';  // CHANGE THIS to your mysql DB password on topcat 
        $dbname = $dbuser . 'db'; // our convention for mysql dbs on topcat   
    } else {  // dev machine, can create pizzadb
        $dbuser = 'pizza_user';
        $dbpass = 'pa55word';  // or your choice
        $dbname = 'pizzadb';
    }
    $dsn = 'mysql:host=localhost;dbname=' . $dbname;
    $dbh = new PDO($dsn, $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
