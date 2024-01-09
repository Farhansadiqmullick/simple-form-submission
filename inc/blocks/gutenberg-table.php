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
    }

    function sfs_table_block_init()
    {
        register_block_type(SFS_PATH . 'build/', [
            'render_callback' => array($this, 'awp_custom_block_render'),
            'attributes' => [
                'tableContent' => [
                    'type' => 'array',
                    'default' => [],
                ],
            ],
        ]);
    }

    function sfs_enqueue_block_editor_assets()
    {
        if (!is_admin()) {
            wp_enqueue_script('frontend-js', SFS_PATH . 'build/frontend.js', ['wp-blocks', 'wp-element'], rand(111, 999), true);
        }
        wp_register_script('sfs-gutenberg-table', SFS_PATH . 'build/index.js', array('wp-blocks', 'wp-element', 'wp-editor'), rand(111, 999), true);
    }


    function awp_custom_block_render($attributes)
    {
        ob_start();
		echo '<div class="gutenberg-table"><pre style="display:none;">' . wp_json_encode($attributes) . '</pre></div>';
		return ob_get_clean();
    }

    public function localized_script()
    {
        $keys = [
            'ajax_url'                      => admin_url('admin-ajax.php'),
            'nonce'                         => wp_create_nonce('sfs_nonce'),
        ];

        return apply_filters('sfs_gutenberg_localize_script', $keys);
    }
}

Gutenberg_Table::init();
