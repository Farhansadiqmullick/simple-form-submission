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

    public function __construct()
    {
        add_action('wp_ajax_validation', array($this, 'sfs_contact'));
        add_action('wp_ajax_nopriv_validation', array($this, 'sfs_contact'));
    }
}
