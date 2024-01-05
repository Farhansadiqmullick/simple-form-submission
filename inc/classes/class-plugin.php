<?php

/**
 * Class Plugin
 * includes/class/Plugin.php
 * Holds the main application logic
 */


defined('ABSPATH') || exit;
/**
 * Class Plugin
 *
 * @version 1.0.0
 */
final class Plugin extends Base
{
	/**
	 * Utilities Trait to use in all classes globally
	 */
	use Utilities;

	public function __construct()
	{
		add_action('plugins_loaded', array($this, 'sfs_plugin_textdomain'));
		add_action('admin_menu', array($this, 'sfs_admin_page'));
		add_action('wp_enqueue_scripts', array($this, 'sfs_frontend_assets'));
		add_action('admin_enqueue_scripts', array($this, 'sfs_backend_assets'));
		add_shortcode('sfs_frontend_form', array($this, 'sfs_frontend_form_shortcode'));
		add_action('wp_footer', array($this, 'sfs_cookie'));
	}


	public function sfs_plugin_textdomain()
	{
		load_plugin_textdomain('sfs', false, SFS_FILE . '/languages');
	}

	public function sfs_frontend_assets()
	{
		wp_enqueue_script('jquery', '//code.jquery.com/jquery-3.6.0.min.js');
		wp_enqueue_style('sfs-frontend', SFS_PATH . 'public/assets/css/frontend.css', '', rand(111, 999), 'all');
		wp_enqueue_script('sfs-frontend', SFS_PATH .  'public/assets/css/frontend.js', ['jquery'], rand(11, 999), true);
		wp_localize_script('sfs-frontend', 'sfs_script', $this->localized_script());
	}

	public function sfs_backend_assets($hook)
	{
		if ('toplevel_sfs_admin' == $hook) {
			wp_enqueue_style('sfs-backend', SFS_PATH . 'src/css/backend.css', '', rand(111, 999), 'all');
			wp_enqueue_script('sfs-frontend', SFS_PATH .  'src/js/backend.js', ['jquery'], rand(11, 999), true);
			wp_localize_script('sfs-frontend', 'sfs_script', $this->localized_script());
		}
	}


	public function sfs_frontend_form_shortcode()
	{
		ob_start();
		include(SFS_PATH . 'template/frontend-form.php');
		return ob_get_clean();
	}

	public function sfs_admin_page()
	{
		add_menu_page(__('Simple Form', 'sfs'), __('Simple Form', 'sfs'), 'edit_theme_options', 'sfs', array($this, 'sfs_table_display'), 'dashicons-feedback');
	}


	/**
	 * Default options
	 *
	 * @version 1.0.0
	 * @return array
	 */
	public function get_default_options()
	{
		$options = [
			'user_ip' => get_user_ip(),
		];

		return apply_filters('sfs_options', $options);
	}

	/**
	 * Get options all options form database
	 *
	 * @version 1.0.0
	 * @return object
	 */
	public function get_options()
	{
		$sfs_options = [];
		foreach ($this->get_default_options() as $key => $value) {
			$sfs_options[$key] = sfs_get_option($key);
		}
		return $sfs_options;
	}

	/**
	 * Localized Scripts
	 *
	 * @version 1.0.0
	 * @return array
	 */
	public function localized_script()
	{
		$keys = [
			'ajax_url'                      => admin_url('admin-ajax.php'),
			'nonce'                         => wp_create_nonce('sfs_nonce'),
			'options'                       => $this->get_options(),
		];

		return apply_filters('sfs_localized_script', $keys);
	}
}
