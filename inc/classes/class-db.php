<?php

class DB extends Base
{
    private $tablename;
    private $charset;

    /**
     * Utilities Trait to use in all classes globally
     */
    use Utilities;

    public function __construct()
    {
        global $wpdb;
        register_activation_hook(SFS_FILE, array($this, 'sfs_database_init'));
        $this->tablename = $wpdb->prefix . 'sfs';
    }

    public function sfs_database_init()
    {
        require_once(ABSPATH . "wp-admin/includes/upgrade.php");
        dbDelta("CREATE TABLE $this->tablename (
id bigint(20) NOT NULL AUTO_INCREMENT,
amount int(10) NOT NULL,
buyer varchar(255) NOT NULL DEFAULT '',
recipt_id varchar(20) NOT NULL DEFAULT '',
items varchar(255) NOT NULL DEFAULT '',
buyer_email varchar(50) NOT NULL DEFAULT '',
buyer_ip varchar(20) NOT NULL DEFAULT '',
note TEXT NOT NULL DEFAULT '',
city varchar(20) NOT NULL DEFAULT '',
phone varchar(20) NOT NULL DEFAULT '',
hash_key varchar(255) NOT NULL DEFAULT '',
entry_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
entry_by int(10) NOT NULL DEFAULT '',
PRIMARY KEY (id)
) $this->charset;");
    }
}
