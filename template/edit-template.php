<?php
function sfs_edit_values($values)
{

    $items = explode(',', $values['items']);
?>

    <div class="sfs-edit-form">
        <h2 class="heading">Edit Form</h2>
        <form class="form-container" action="" method="POST">
            <div class="form-group">
                <label for="amount">Amount *</label>
                <input type="number" id="amount" name="amount" value="<?php echo intval($values['amount']); ?>" min="1">
            </div>
            <div class="form-group">
                <label for="buyer">Buyer *</label>
                <input type="text" id="buyer" name="buyer" value="<?php echo esc_attr($values['buyer']); ?>" maxlength="20">
            </div>
            <div class="form-group">
                <label for="receipt_id">Receipt ID *</label>
                <input type="text" id="receipt_id" name="receipt_id" value="<?php echo esc_attr($values['receipt_id']); ?>" maxlength="20">
            </div>
            <div class="form-group">
                <label for="items">Items *</label>
                <input type="text" name="items" id="items" placeholder="Enter items and separate with commas" value="">
                <div class="item-container" id="itemsContainer">
                    <?php
                    foreach ($items as $item) {
                        printf('<div class="tag">%s<span class="remove-item">&times;</span></div>', esc_attr($item));
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="buyer_email">Buyer Email *</label>
                <input type="email" id="buyer_email" name="buyer_email" value="<?php echo esc_attr($values['buyer_email']); ?>" maxlength="50">
            </div>
            <div class="form-group">
                <label for="note">Note *</label>
                <textarea id="note" name="note" rows="4"><?php echo esc_html($values['note']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="city">City *</label>
                <input type="text" id="city" name="city" value="<?php echo esc_attr($values['city']); ?>" maxlength="20">
            </div>
            <div class="form-group">
                <label for="phone">Phone *</label>
                <input type="number" id="phone" name="phone" value="<?php echo esc_attr($values['phone']); ?>">
            </div>
            <div class="form-group">
                <label for="entry_by">Entry By *</label>
                <input type="number" id="entry_by" name="entry_by" value="<?php echo esc_attr($values['entry_by']); ?>" min="1">
            </div>
            <button name="submit" type="submit">Update</button>
        </form>
    </div>

<?php
} ?>