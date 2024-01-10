<?php

defined('ABSPATH') || exit;
/**
 * Class Plugin
 *
 * @version 1.0.0
 */
final class Gutenberg_Table
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

    /**
     * Hook init
     *
     * @version 1.0.0
     * @return void
     */
    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        self::$instance->add_actions();
    }

    public function add_actions()
    {
        add_action('init', array($this, 'sfs_table_block_init'));
        add_action('enqueue_block_editor_assets', array($this, 'sfs_enqueue_block_editor_assets'));
        add_action('rest_api_init', array($this, 'sfs_get_frontend_table'));
    }

    function sfs_table_block_init()
    {
        register_block_type(SFS_PATH . 'build/sfs-frontend', [
            'render_callback' => array($this, 'sfs_load_frontend_form'),
        ]);

        register_block_type(SFS_PATH . 'build/sfs-backend', [
            'render_callback' => array($this, 'sfs_load_backend_table'),
        ]);
    }

    function sfs_get_frontend_table()
    {
        register_rest_route('sfs/v1', 'sfs-frontend-form-content', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_sfs_frontend_form_content'],
            'permission_callback' => '__return_true',
        ));
    }

    function sfs_enqueue_block_editor_assets()
    {
        wp_register_script('sfs-gutenberg-table', SFS_PATH . 'build/sfs-backend/index.js', array('wp-blocks', 'wp-element', 'wp-editor'), rand(111, 999), true);
        wp_register_script('sfs-frontend-form', SFS_PATH . 'build/sfs-frontend/index.js', array('wp-blocks', 'wp-element', 'wp-editor'), rand(111, 999), true);
    }


    function sfs_load_frontend_form($attributes)
    {
        ob_start();
        include(SFS_PATH . 'template/frontend-form.php');
        return ob_get_clean();
    }


    function sfs_load_backend_table($attributes)
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'sfs';
        $sfs_values = $wpdb->get_results("SELECT id, amount, buyer, receipt_id, items, buyer_email, buyer_ip, note, city, phone, hash_key, entry_at, entry_by from {$tablename} ORDER BY id DESC", ARRAY_A);
        $table_html = $this->generate_sfs_table_html($sfs_values);

        return $table_html;
    }

    function generate_sfs_table_html($data)
    {
        if (empty($data)) {
            return '<p>No data available.</p>';
        }

        $html = '<table>';
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


    function get_sfs_frontend_form_content()
    {
        ob_start();
        include(SFS_PATH . 'template/frontend-form.php');
        $content = ob_get_clean();

        return array(
            'content' => $content,
        );
    }
}

Gutenberg_Table::init();
