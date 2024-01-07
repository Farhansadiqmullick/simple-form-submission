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
            'edit' => sprintf('<a href="#" class="edit-item" data-item-id="%s" data-nonce="%s">Edit</a>', $item['id'], esc_attr(wp_create_nonce('edit_item'))),
            'delete' => sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', $item['id'], esc_attr(wp_create_nonce('delete-item'))),
        );

        return $this->row_actions($actions);
    }


    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function process_bulk_action()
    {
        if ('edit' === $this->current_action()) {
            // Handle edit action here
            $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
            if ($id) {
                check_admin_referer('edit-item');

                $item_data = get_data_from_database($id);

                if ($item_data) {
                    // Extract data for the input field
                    // $value = esc_attr($item_data['value']);

                    // Create the HTML for the popup form
?>
                    <div id="edit-popup" class="sfs-edit-popup">
                        <form method="post" action="">
                            <input type="hidden" name="item_id" value="<?php echo $id; ?>">
                            <label for="item_value">Value:</label>
                            <input type="text" name="item_value" value="">
                            <input type="submit" name="update_item" value="Update">
                        </form>
                    </div>
<?php
                }
            }
        } elseif ('delete' === $this->current_action()) {
            // Handle delete action here
            $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
            if ($id) {
                check_admin_referer('delete-item');

                // Delete the item by ID
                // You can create a custom function for deleting the item.
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
