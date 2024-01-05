<div class="sfs-form">
    <h2 class="heading">Simple Form Submission</h2>
    <form class="form-container" action="" method="POST">
        <div class="form-group">
            <label for="amount">Amount *</label>
            <input type="number" id="amount" name="amount" required min="10">
        </div>
        <div class="form-group">
            <label for="buyer">Buyer *</label>
            <input type="text" id="buyer" name="buyer" required maxlength="20">
        </div>
        <div class="form-group">
            <label for="receipt_id">Receipt ID *</label>
            <input type="text" id="receipt_id" name="receipt_id" required maxlength="20">
        </div>
        <div class="form-group">
            <label for="items">Items *</label>
            <select id="items" name="items" multiple>
                <option value="item1">Item 1</option>
                <option value="item2">Item 2</option>
                <option value="item3">Item 3</option>
                <option value="item4">Item 4</option>
                <option value="item5">Item 5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="buyer_email">Buyer Email *</label>
            <input type="email" id="buyer_email" name="buyer_email" required maxlength="50">
        </div>
        <div class="form-group">
            <label for="note">Note *</label>
            <textarea id="note" name="note" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="city">City *</label>
            <input type="text" id="city" name="city" required maxlength="20">
        </div>
        <div class="form-group">
            <label for="phone">Phone *</label>
            <input type="number" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="entry_by">Entry By *</label>
            <input type="number" id="entry_by" name="entry_by" required>
        </div>
        <button name="submit" type="submit">Submit</button>
    </form>
</div>