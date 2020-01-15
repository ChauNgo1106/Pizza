<?php

function get_orders_by_user_and_status($db, $user_id, $status) {
    global $httpClient;
    global $base_url;
    $all_orders = rest_get_orders($httpClient, $base_url);
    $orders = array();
    foreach ($all_orders as $order) {
        if ($order['user_id'] == $user_id && $order['status'] === $status) {
            $orders[] = $order;
        }
    }
    error_log('get_preparing_orders_by_user_id, all: ' . $status . $user_id . print_r($all_orders, true));
    error_log('get_preparing_orders_by_user_id ' . print_r($orders, true));
    return $orders;
}

function get_preparing_orders_by_user($db, $user_id) {
    return get_orders_by_user_and_status($db, $user_id, 'Preparing');
}

function get_baked_orders_by_user($db, $user_id) {
    return get_orders_by_user_and_status($db, $user_id, 'Baked');
}

function update_to_finished($db, $user_id) {
    global $httpClient;
    global $base_url;
    error_log("update_to_finished for user $user_id");
    $orders = get_orders_by_user_and_status($db, $user_id, 'Baked');
    foreach ($orders as $order) {
        error_log("found Baked order, upd to finished...");
        $order['status'] = 'Finished';
        rest_put_order($httpClient, $base_url, $order);
    }
}

function add_order($db, $user_id, $size, $current_day, $status, $topping_ids) {
    global $httpClient;
    global $base_url;
    error_log('add_order: size = ' . $size . ' $topping_ids = ' . print_r($topping_ids, true));
    $order = array("user_id" => $user_id, "size" => $size, "day" => $current_day,
        "status" => $status);
    foreach ($topping_ids as $t) {
        $topping_name = rest_get_topping($httpClient, $base_url, $t)['topping'];
        $order["toppings"][] = $topping_name;
    }
    rest_post_order($httpClient, $base_url, $order);
}
