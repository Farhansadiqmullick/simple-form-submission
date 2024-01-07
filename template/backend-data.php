<?php

defined('ABSPATH') || exit;
?>
<h3>Simple Form Report</h3>

<div class="form_box">
    <div class="form_box_content">
        <?php
        global $wpdb;
        $tablename = $wpdb->prefix . 'sfs';
        $sfs_values = $wpdb->get_results("SELECT id, amount, buyer, receipt_id, items, buyer_email, buyer_ip, note, city, phone, hash_key, entry_at, entry_by from {$tablename} ORDER BY id DESC", ARRAY_A);
        $sfs_contents = new SFS_TABLE($sfs_values);
        $sfs_contents->prepare_items();
        $sfs_contents->display();
        ?>
    </div>
</div>

<div class="edit-wrapper"></div>