<?php

function get_current_day($db) {
    global $httpClient;
    global $base_url;
    return rest_get_day($httpClient, $base_url);
}



