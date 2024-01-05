<?php

/**
 * Trait Utilities
 * includes/helper/Utilities.php
 * Utilities Trait to use in all classes globally
 */

defined('ABSPATH') || die('No script kiddies please!');

/**
 * Trait Utilities
 */
trait Utilities
{

    /**
     * Load Class
     *
     * @param string $class_name The class name.
     */
    public function load_class($class_name)
    {
        if (file_exists(SFS_INC . 'classes/' . $class_name . '.php')) {
            include SFS_INC . 'classes/' . $class_name . '.php';
        }
    }

    /**
     * Load File
     *
     * @param string $file file name.
     */

    public function load_file($file)
    {
        if (file_exists(SFS_INC . $file . '.php')) {
            include SFS_INC . $file . '.php';
        }
    }
}
