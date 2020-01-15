<?php

function get_users($db) {
    global $httpClient;
    global $base_url;
    return rest_get_users($httpClient, $base_url);    
}
