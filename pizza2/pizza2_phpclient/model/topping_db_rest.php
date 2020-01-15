<?php

function get_toppings($db) {
    global $httpClient;
    global $base_url;
    return rest_get_toppings($httpClient, $base_url);    
}
?>