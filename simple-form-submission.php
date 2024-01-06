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
