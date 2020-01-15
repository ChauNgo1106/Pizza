<?php
// the try/catch for these actions is in the caller, index.php

function get_sizes($db) {
    global $httpClient;
    global $base_url;
    return rest_get_sizes($httpClient, $base_url);    
}
