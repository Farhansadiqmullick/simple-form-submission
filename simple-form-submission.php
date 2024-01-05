<?php

/** 
 * Plugin Name: Simple Form Submission
 * Plugin URI: https://github.com/farhansadiqmullick/simple-form-submission
 * Description: Simple form Submission and Display in Front End
 * Version: 1.0
 * Author: Farhan
 * Aurthor URI: https://farhanmullick.com
 * License: GPLv2 or later
 * Text Domain: sfs
 * Domain Path: /languages/
 */

if (!defined('ABSPATH'))  exit;

define('SFS_DB_VERSION', 1.0);
define('SFS_FILE', __FILE__);


if (file_exists(__DIR__ . '/inc/boot.php')) {
    require_once __DIR__ . '/inc/boot.php';
}
class SFS
{


    function vform_admin_page()
    {
        add_menu_page(__('Validation Form', 'sfs'), __('Validation Form', 'sfs'), 'manage_options', 'sfs', array($this, 'vform_table_display'), 'dashicons-feedback');
    }

    function vform_table_display()
    {
        // include_once 'inc/dataset.php';

        global $wpdb;
        echo '<h2>Validation Form</h2>';

        global $wpdb;
        $vformUsers = $wpdb->get_results("SELECT id, name, email, phone, zipcode from {$wpdb->prefix}sfs ORDER BY id DESC", ARRAY_A);
        $validUsers = new VFORM_DATA($vformUsers);


        /**
         * Filtering the data with Name 
         */
        function vform_search_by_name($item)
        {
            $name = strtolower($item['name']);
            $search = sanitize_text_field($_REQUEST['s']);

            if (strpos($name, $search) !== false) {
                return true;
            }
            return false;
        }

        if (isset($_REQUEST['s'])) {
            $vformUsers = array_filter($vformUsers, 'vform_search_by_name');
        }

        $validUsers->set_data($vformUsers);
        $validUsers->prepare_items();
?>

        <div class="wrap">
            <form method="get">
                <?php
                $validUsers->search_box('search', 'valid_search');
                $validUsers->display(); ?>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
            </form>
        </div>
<?php

    }

    function vform_contact()
    {
        $nonce = sanitize_text_field($_POST['nonce']);
        var_dump($nonce);
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $zipcode = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';

        if (wp_verify_nonce($nonce, 'sfs')) {
            $name = sanitize_text_field($name);
            $email = sanitize_text_field($email);
            $phone = sanitize_text_field($phone);
            $zipcode = sanitize_text_field($zipcode);
            global $wpdb;

            $wpdb->insert("{$wpdb->prefix}sfs", ['name' => $name, 'email' => $email, 'phone' => $phone, 'zipcode' => $zipcode]);
            wp_redirect(admin_url('admin.php?page=sfs'));
        }
    }


    function vform_frontend()
    {
        include_once 'inc/frontend/form.php';
    }
}

new SFS();
