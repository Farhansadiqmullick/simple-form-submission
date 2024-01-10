<?php

// Get User IP conveniently
function get_user_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


function generate_random_salt()
{
    $characters = '123456789abcdefghijklmnopqrstuvwxyz';
    $salt = '';
    for ($i = 0; $i < 3; $i++) {
        $salt .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $salt;
}


function sha512hashing($string, $salt)
{
    $string_with_salt = $string . $salt;
    $hashedString = hash('sha512', $string_with_salt);

    return $hashedString;
}

function get_data_from_database($id)
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'sfs';

    $query = $wpdb->prepare("SELECT * FROM $tablename WHERE id = %d", $id);
    $values = $wpdb->get_row($query, ARRAY_A);

    return $values;
}

function sfs_delete_item($id)
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'sfs';
    if (is_numeric($id) && $id > 0) {
        $wpdb->delete($tablename, array('id' => $id), array('%d'));
    }
}
