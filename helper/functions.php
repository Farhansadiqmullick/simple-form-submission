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

function generate_sfs_table_html($data)
{
    if (empty($data)) {
        return '<p>No data available.</p>';
    }

    $html = '<table class="sfs_table">';
    $html .= '<thead><tr><th>ID</th><th>Amount</th><th>Buyer</th><th>Receipt ID</th><th>Items</th><th>Buyer Email</th><th>Buyer IP</th><th>Note</th><th>City</th><th>Phone</th><th>Hash Key</th><th>Entry At</th><th>Entry By</th></tr></thead>';
    $html .= '<tbody>';

    foreach ($data as $row) {
        $html .= '<tr>';
        $html .= '<td>' . intval($row['id']) . '</td>';
        $html .= '<td>' . esc_attr($row['amount']) . '</td>';
        $html .= '<td>' . esc_html($row['buyer']) . '</td>';
        $html .= '<td>' . esc_html($row['receipt_id']) . '</td>';
        $html .= '<td>' . esc_html($row['items']) . '</td>';
        $html .= '<td>' . esc_html($row['buyer_email']) . '</td>';
        $html .= '<td>' . esc_html($row['buyer_ip']) . '</td>';
        $html .= '<td>' . esc_html($row['note']) . '</td>';
        $html .= '<td>' . esc_html($row['city']) . '</td>';
        $html .= '<td>' . esc_html($row['phone']) . '</td>';
        $html .= '<td>' . esc_html($row['hash_key']) . '</td>';
        $html .= '<td>' . esc_html($row['entry_at']) . '</td>';
        $html .= '<td>' . intval($row['entry_by']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
}

function is_editor_or_admin_logged_in()
{
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        if (in_array('editor', $current_user->roles) || in_array('administrator', $current_user->roles)) {
            return true;
        }
    }

    return false;
}
