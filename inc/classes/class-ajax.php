<?php

/**
 * Class Ajax
 * includes/class/Plugin.php
 * Holds the main application logic
 */


defined('ABSPATH') || exit;
/**
 * Class Plugin
 *
 * @version 1.0.0
 */
final class Ajax extends Base
{
    /**
     * Utilities Trait to use in all classes globally
     */
    use Utilities;

    /**
     * Singleton mode
     *
     * @var null
     */
    public static $instance = null;

    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        self::$instance->add_actions();
    }

    public function add_actions()
    {
        add_action('wp_ajax_sfs_frontend_validation', array($this, 'sfs_contact'));
        add_action('wp_ajax_nopriv_sfs_frontend_validation', array($this, 'sfs_contact'));
    }

    public function sfs_contact()
    {

        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sfs_nonce')) {
            wp_send_json_error(['error' => 'Unauthorized Access']);
        }

        $validated_data = $this->validate_and_sanitize_data($_POST);

        if (empty($validated_data)) {
            wp_send_json_error(['error' => 'Invalid Data']);
        }

        $inserted = $this->sfs_insert_data_into_db($validated_data);

        if (!$inserted) {
            wp_send_json_error(['error' => 'Database Insertion Error']);
        }

        error_log('all the values' . print_r($validated_data, true));
        wp_send_json_success(['data' => json_encode($validated_data)]);
        wp_die();
    }

    private function validate_and_sanitize_data($data)
    {
        date_default_timezone_set('Asia/Dhaka');
        $values = ['amount', 'buyer', 'receipt_id', 'items', 'buyer_email', 'buyer_ip', 'note', 'city', 'phone', 'hash_key', 'entry_at', 'entry_by'];

        $filteredData = [];

        if (isset($data) && is_array($data)) {
            foreach ($values as $key) {
                // Check if the key exists in $data
                if (array_key_exists($key, $data)) {
                    switch ($key) {
                        case 'amount':
                            $filteredData[$key] = intval($data[$key]);
                            break;
                        case 'items':
                            if (is_array($data[$key])) {
                                $filteredData[$key] = implode(',', $data[$key]);
                            }
                            break;
                        case 'buyer_ip':
                            $filteredData[$key] = get_user_ip();
                            break;
                        case 'entry_at':
                            $filteredData[$key] = date('Y-m-d H:i:s');
                            break;
                        case 'hash_key':
                            if (isset($data['receipt_id'])) {
                                $filteredData[$key] = sha512hashing($data['receipt_id'], generate_random_salt());
                                error_log("hash value". print_r($filteredData[$key], true));
                            }
                            break;
                        default:
                            $filteredData[$key] = $data[$key];
                            break;
                    }
                }
            }
        }

        return $filteredData;
    }


    private function sfs_insert_data_into_db($data)
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'sfs';

        if (isset($data) && is_array($data) && !empty($data)) {
            $row = $data;
            $columns = implode(", ", array_keys($row));
            $placeholders = implode(", ", array_fill(0, count($row), "%s"));

            $query = $wpdb->prepare("INSERT INTO $tablename ($columns) VALUES ($placeholders)", array_values($row));
            return $wpdb->query($query);
        }

        return false;
    }
}

Ajax::init();
