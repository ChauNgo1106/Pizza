<?php

// Functions to do the needed web service requests
// Note that all needed web services are sent from this directory
// The functions here should throw up to their callers, just like
// the functions in model.
//
// use Composer autoloader, so we don't have to require Guzzle PHP files
require '../vendor/autoload.php';
$httpClient = new \GuzzleHttp\Client();
// We are assuming the server site is a sibling of this site on
// the webserver, not the usual setup but convenient for us.
$dirs = explode(DIRECTORY_SEPARATOR, __DIR__);
array_pop($dirs); // remove last element (this dir)
array_pop($dirs); // remove this site root dir
$project_parent = implode('/', $dirs) . '/';
$doc_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
// parent_path is the part of $project_parent past $doc_root
$parent_path = substr($project_parent, strlen($doc_root));
// base_url of the server site is child of this site's parent:
$base_url = "http://localhost" . $parent_path . 'pizza2_server/api/';

function rest_get_day($httpClient, $base_url) {
    $url = $base_url . 'day';
    $response = $httpClient->get($url);
    $day = $response->getBody()->getContents();
    return $day;
}

// POST order and get back location (URL of new order)
function rest_post_order($httpClient, $base_url, $order) {
    error_log("rest_post_order " . print_r($order, true));
    $url = $base_url . 'orders';
    // Guzzle does the json_encode for us--
    $response = $httpClient->request('POST', $url, ['json' => $order]);
    $location = $response->getHeader('Location');
    return $location[0];  // first entry in array is string Location URL
}

function rest_put_order($httpClient, $base_url, $order) {
    $url = $base_url . 'orders' . '/' . $order['id'];
    // Guzzle does the json_encode for us--
    $httpClient->request('PUT', $url, ['json' => $order]);
}

function rest_get_orders($httpClient, $base_url) {
    $url = $base_url . 'orders';
    $response = $httpClient->get($url);
    $ordersJson = $response->getBody()->getContents();
    // print_r($productsJson);
    $orders = json_decode($ordersJson, true);
    error_log('orders After json_decode:');
    error_log(print_r($orders, true));
    return $orders;
}

function rest_get_order($httpClient, $base_url, $orderID) {
    $url = $base_url . 'orders/' . $orderID;
    $response = $httpClient->get($url);
    $orderJson = $response->getBody()->getContents();  // as StreamInterface, then string
    $order = json_decode($orderJson, true);
    return $order;
}

function rest_get_toppings($httpClient, $base_url) {
    error_log('rest_get_toppings');
    $url = $base_url . 'toppings';
    $response = $httpClient->get($url);
    $toppingsJson = $response->getBody()->getContents();
    $toppings = json_decode($toppingsJson, true);
    error_log('toppings After json_decode:' . print_r($toppings, true));
    return $toppings;
}

function rest_get_topping($httpClient, $base_url, $toppingID) {
    error_log('rest_get_topping, id = ' . $toppingID);
    $url = $base_url . 'toppings/' . $toppingID;
    $response = $httpClient->get($url);
    $toppingJson = $response->getBody()->getContents();  // as StreamInterface, then string
    $topping = json_decode($toppingJson, true);
    error_log('rest_get_topping, topping = ' . print_r($topping, true));
    return $topping;
}

function rest_get_sizes($httpClient, $base_url) {
    // echo 'rest_get_sizes ';
    $url = $base_url . 'sizes';
    // echo 'url = ' . $url;
    $response = $httpClient->get($url);
    $sizesJson = $response->getBody()->getContents();
    $sizes = json_decode($sizesJson, true);
    error_log('sizes After json_decode:');
    error_log(print_r($sizes, true));
    return $sizes;
}

function rest_get_users($httpClient, $base_url) {
    $url = $base_url . 'users';
    //echo 'url = ' . $url;
    $response = $httpClient->get($url);
    $usersJson = $response->getBody()->getContents();
    $users = json_decode($usersJson, true);
    error_log('users After json_decode:');
    error_log(print_r($users, true));
    return $users;
}

