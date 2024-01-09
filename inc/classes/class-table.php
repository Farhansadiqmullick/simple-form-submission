<?php

defined('ABSPATH') || exit;

if (!class_exists("WP_List_Table")) {
    require_once ABSPATH . '/wp-admin/includes/class-wp-list-table.php';
}

class SFS_TABLE extends WP_List_Table
{
    private $per_page = 10;
    private $total_items;
    private $current_page;
    function __construct($data)
    {
        parent::__construct();
        $this->items = $data;
    }

    function get_columns()
    {
        return [
            'cb'     => '<input type="checkbox">',
            'amount'   => __('Amount', 'sfs'),
            'buyer'   => __('Buyer', 'sfs'),
            'receipt_id'   => __('Receipt Id', 'sfs'),
            'items'   => __('Items', 'sfs'),
            'buyer_email'   => __('Buyer Email', 'sfs'),
            'note'   => __('Note', 'sfs'),
            'city'   => __('City', 'sfs'),
            'phone'   => __('Phone', 'sfs'),
            'hash_key'   => __('Hash Key', 'sfs'),
            'entry_at'   => __('Entry At', 'sfs'),
            'entry_by'   => __('Entry By', 'sfs'),
            'action' => __('Action', 'sfs')
        ];
    }

    function column_cb($item)
    {
        return "<input type='checkbox' value='{$item['id']}' name='bulk-delete[]'>";
    }

    function column_action($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="#" class="edit-item" data-item-id="%s" data-nonce="%s">Edit</a>', $item['id'], esc_attr(wp_create_nonce('edit-item'))),
            'delete' => sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s" onclick="return confirm(\'Are you sure you want to delete the id?\')">Delete</a>', esc_attr($_REQUEST['page']), 'delete', $item['id'], esc_attr(wp_create_nonce('delete-item'))),
        );
        
        return $this->row_actions($actions);
    }


    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function process_bulk_action()
    {
        if ('delete' === $this->current_action()) {
            // Handle delete action here
            $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
            if ($id) {
                check_admin_referer('delete-item');
                sfs_delete_item($id);
            }
        }
    }


    function prepare_items()
    {
        $this->_column_headers = array($this->get_columns(), [], []);
        $this->current_page = $this->get_pagenum();
        $this->total_items = count($this->items);

        // Set the pagination arguments
        $pagination_args = array(
            'total_items' => $this->total_items,
            'per_page'    => $this->per_page,
            'total_pages' => ceil($this->total_items / $this->per_page),
        );

        // Create the pagination links
        $this->set_pagination_args($pagination_args);

        // Calculate the offset for the current page
        $offset = ($this->current_page - 1) * $this->per_page;

        // Get the items for the current page
        $this->items = array_slice($this->items, $offset, $this->per_page);

        $this->process_bulk_action();
    }
}
