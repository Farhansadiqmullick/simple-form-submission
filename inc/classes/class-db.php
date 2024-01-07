<?php

defined('ABSPATH') || exit;

class DB extends Base
{
    private $tablename;
    private $charset;

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
        register_activation_hook(SFS_FILE, array($this, 'sfs_database_init'));
    }

    public function sfs_database_init()
    {
        global $wpdb;
        $this->tablename = $wpdb->prefix . 'sfs';
        $this->charset = $wpdb->get_charset_collate();

        require_once(ABSPATH . "wp-admin/includes/upgrade.php");

        $sql = "CREATE TABLE $this->tablename (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            amount int(10) NOT NULL,
            buyer varchar(255) NOT NULL DEFAULT '',
            receipt_id varchar(20) NOT NULL DEFAULT '',
            items varchar(255) NOT NULL DEFAULT '',
            buyer_email varchar(50) NOT NULL DEFAULT '',
            buyer_ip varchar(20) NOT NULL DEFAULT '',
            note TEXT NOT NULL DEFAULT '',
            city varchar(20) NOT NULL DEFAULT '',
            phone varchar(20) NOT NULL DEFAULT '',
            hash_key varchar(255) NOT NULL DEFAULT '',
            entry_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            entry_by int(10) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset;";

        dbDelta($sql);
    }
}

DB::init();
