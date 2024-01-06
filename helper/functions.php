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


function generate_random_salt() {
    $characters = '123456789abcdefghijklmnopqrstuvwxyz';
    $salt = '';
    for ($i = 0; $i < 8; $i++) {
        $salt .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $salt;
}


function sha512hashing($string, $salt) {
    $string_with_salt = $string . $salt;
    $hashedString = hash('sha512', $string_with_salt);

    return $hashedString;
}



