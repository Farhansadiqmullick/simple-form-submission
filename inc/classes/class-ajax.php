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

        add_action('wp_ajax_sfs_edit_item', array($this, 'sfs_update_item'));
        add_action('wp_ajax_sfs_backend_validation', array($this, 'sfs_update_form'));

        add_action('wp_ajax_fetch_sfs_fetch_gutenberg_table', 'sfs_fetch_gutenberg_table_data');
        add_action('wp_ajax_nopriv_sfs_fetch_gutenberg_table', 'sfs_fetch_gutenberg_table_data');
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
        if ($data['action'] == "sfs_backend_validation") {
            $values = ['id', 'amount', 'buyer', 'receipt_id', 'items', 'buyer_email', 'buyer_ip', 'note', 'city', 'phone', 'hash_key', 'entry_at', 'entry_by'];
        } else {
            $values = ['amount', 'buyer', 'receipt_id', 'items', 'buyer_email', 'buyer_ip', 'note', 'city', 'phone', 'hash_key', 'entry_at', 'entry_by'];
        }

        $filteredData = [];


        if (isset($data) && is_array($data)) {
            foreach ($values as $key) {
                switch ($key) {
                    case 'amount':
                        $filteredData[$key] = intval($data[$key]);
                        break;
                    case 'items':
                        if (is_array($data[$key])) {
                            $filteredData[$key] = sanitize_text_field(implode(',', str_replace(['&times;', ','], '', $data[$key])));
                        }
                        break;
                    case 'buyer_ip':
                        $filteredData[$key] = $data[$key]['user_ip'] ? sanitize_text_field($data[$key]['user_ip']) : '';
                        break;
                    case 'entry_at':
                        $filteredData[$key] = date('Y-m-d H:i:s');
                        break;
                    case 'hash_key':
                        if (isset($data['receipt_id'])) {
                            $hashing = sha512hashing($data['receipt_id'], generate_random_salt());
                            $filteredData[$key] = $hashing;
                        }
                        break;
                    default:
                        if ('string' == gettype($key)) {
                            $filteredData[$key] = sanitize_text_field($data[$key]);
                        } else if ('int' == gettype($key)) {
                            $filteredData[$key] = intval($data[$key]);
                        }
                        break;
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

    public function sfs_update_item()
    {
        error_log('verify nonce' . $_POST['nonce']);
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'edit-item')) {
            wp_send_json_error(['error' => 'Unauthorized Access']);
        }

        if (isset($_POST['id'])) {
            $id = absint($_POST['id']);
            $values = get_data_from_database($id);
            include(SFS_PATH . 'template/edit-template.php');
            sfs_edit_values($values);
        }

        wp_die();
    }

    public function sfs_update_form()
    {
        // if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'edit-item')) {
        //     wp_send_json_error(['error' => 'Unauthorized Access']);
        // }

        // check_admin_referer( '_wpnonce' );

        $validated_data = $this->validate_and_sanitize_data($_POST);

        if (empty($validated_data)) {
            wp_send_json_error(['error' => 'Invalid Data']);
        }

        $updated = $this->sfs_update_data_into_db($validated_data);

        if (!$updated) {
            wp_send_json_error(['error' => 'Database Insertion Error']);
        }
        wp_send_json_success(['data' => json_encode($validated_data)]);
        wp_die();
    }

    private function sfs_update_data_into_db($data)
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'sfs';

        if (isset($data['id']) && is_numeric($data['id'])) {
            $id = $data['id'];
            if (!empty($data)) {
                $keys = array();
                $values = array();
                foreach ($data as $column => $value) {
                    if ($column != 'id') {
                        $keys[] = "$column = %s";
                        $values[] = $value;
                    }
                }
                $set_clause = implode(', ', $keys);
                $args = array("UPDATE $tablename SET $set_clause WHERE id = %d");
                $args = array_merge($args, $values);
                $args = array_merge($args, array($id));

                $query = call_user_func_array(array($wpdb, 'prepare'), $args);
                return $wpdb->query($query);
            }
        }

        return false;
    }

    //Gutenberg Block Ajax
    function sfs_fetch_gutenberg_table_data()
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'sfs';
        $sfs_values = $wpdb->get_results("SELECT id, amount, buyer, receipt_id, items, buyer_email, buyer_ip, note, city, phone, hash_key, entry_at, entry_by from {$tablename} ORDER BY id DESC", ARRAY_A);

        // Return the data as JSON
        wp_send_json(json_encode($sfs_values));
        wp_die();
    }
}

Ajax::init();
