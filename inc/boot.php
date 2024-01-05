<?php


defined( 'ABSPATH' ) || exit;

/**
 * Required constants for the plugin
 */
define( 'SFS_PREFIX', 'sfs_' );

define( 'SFS_PATH', plugin_dir_path( SFS_FILE ) );
define( 'SFS_URL', plugin_dir_url( SFS_URL ) );
define( 'SFS_INC', SFS_PATH . 'inc/' );
define( 'SFS_PUBLIC', SFS_URL . 'public/' );

/**
 * Common functions outside OOP
 */

if ( file_exists( SFS_INC . 'helper/functions.php' ) ) {
	require_once SFS_INC . 'helper/functions.php';
}

/**
 * Required classes
 */
if ( file_exists( SFS_INC . 'classes/class-base.php' ) ) {
	require_once SFS_INC . 'classes/class-base.php';
}

if ( file_exists( SFS_INC . 'classes/class-db.php' ) ) {
	require_once SFS_INC . 'classes/class-db.php';
}

if ( file_exists( SFS_INC . 'classes/class-plugin.php' ) ) {
	require_once SFS_INC . 'classes/class-plugin.php';
}


if ( file_exists( SFS_INC . 'classes/class-table.php' ) ) {
	require_once SFS_INC . 'classes/class-table.php';
}


/**
 * Load ajax
 */
if ( file_exists( SFS_INC . 'classes/class-ajax.php' ) && wp_doing_ajax() ) {
	require_once SFS_INC . 'classes/class-ajax.php';
}

?>