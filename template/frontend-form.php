<div class="sfs-form">
    <h2 class="heading">Simple Form Submission</h2>
    <form class="form-container" action="" method="POST">
        <div class="form-group">
            <label for="amount">Amount *</label>
            <input type="number" id="amount" name="amount" value="" required min="1">
        </div>
        <div class="form-group">
            <label for="buyer">Buyer *</label>
            <input type="text" id="buyer" name="buyer" value="" required maxlength="20">
        </div>
        <div class="form-group">
            <label for="receipt_id">Receipt ID *</label>
            <input type="text" id="receipt_id" name="receipt_id" value="" required maxlength="20">
        </div>
        <div class="form-group">
            <label for="items">Items *</label>
            <input type="text" name="items" id="items" placeholder="Enter items and separate with commas" value="">
            <div class="item-container" id="itemsContainer">
            </div>
        </div>
        <div class="form-group">
            <label for="buyer_email">Buyer Email *</label>
            <input type="email" id="buyer_email" name="buyer_email" value="" required maxlength="50">
        </div>
        <div class="form-group">
            <label for="note">Note *</label>
            <textarea id="note" name="note" rows="4" value="" required></textarea>
        </div>
        <div class="form-group">
            <label for="city">City *</label>
            <input type="text" id="city" name="city" value="" required maxlength="20">
        </div>
        <div class="form-group">
            <label for="phone">Phone *</label>
            <input type="number" id="phone" name="phone" value="" required>
        </div>
        <div class="form-group">
            <label for="entry_by">Entry By *</label>
            <input type="number" id="entry_by" name="entry_by" value="" required min="1">
        </div>
        <button name="submit" type="submit">Submit</button>
    </form>
</div>