<?php

/**
 * Abstract Class Base
 * Base class of the plugin
 *
 */

defined('ABSPATH') || exit;


/**
 * Class Base
 *
 * @version  1.0
 */
abstract class Base
{
    /**
     * Utilities Trait to use in all classes globally
     */
    use Utilities;

    /**
     * Instance of the Core App
     *
     * @var mixed
     */
    protected $plugin = null;
    /**
     * Instance of the Core App
     *
     * @var null
     */
    public static $instance = null;
}
