<div class="sfs-form">
    <h2 class="heading">Simple Form Submission</h2>
    <form class="form-container" action="" method="POST">
        <div class="form-group">
            <label for="amount">Amount *</label>
            <input type="number" id="amount" name="amount" value="">
        </div>
        <div class="form-group">
            <label for="buyer">Buyer *</label>
            <input type="text" id="buyer" name="buyer" value="">
        </div>
        <div class="form-group">
            <label for="receipt_id">Receipt ID *</label>
            <input type="text" id="receipt_id" name="receipt_id" value="">
        </div>
        <div class="form-group">
            <label for="items">Items *
            <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                    <path fill="#555" d="M11 17h2v-6h-2zm1-8q.425 0 .713-.288T13 8q0-.425-.288-.712T12 7q-.425 0-.712.288T11 8q0 .425.288.713T12 9m0 13q-2.075 0-3.9-.788t-3.175-2.137q-1.35-1.35-2.137-3.175T2 12q0-2.075.788-3.9t2.137-3.175q1.35-1.35 3.175-2.137T12 2q2.075 0 3.9.788t3.175 2.137q1.35 1.35 2.138 3.175T22 12q0 2.075-.788 3.9t-2.137 3.175q-1.35 1.35-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12q0-3.35-2.325-5.675T12 4Q8.65 4 6.325 6.325T4 12q0 3.35 2.325 5.675T12 20m0-8" />
                </svg>
                <span class="tooltip">Please write the Items and have a comma after writing</span>
            </div>
            </label>
            <input type="text" name="items" id="items" placeholder="Enter items and separate with commas" value="">
            <div class="item-container" id="itemsContainer">
            </div>
        </div>
        <div class="form-group">
            <label for="buyer_email">Buyer Email *</label>
            <input type="email" id="buyer_email" name="buyer_email" value="">
        </div>
        <div class="form-group">
            <label for="note">Note *</label>
            <textarea id="note" name="note" rows="4" value=""></textarea>
        </div>
        <div class="form-group">
            <label for="city">City *</label>
            <input type="text" id="city" name="city" value="">
        </div>
        <div class="form-group">
            <label for="phone">Phone *</label>
            <div class="phone-prefix">
                <span>+880</span>
                <input type="number" id="phone" name="phone" value="">
            </div>
        </div>
        <div class="form-group">
            <label for="entry_by">Entry By *</label>
            <input type="number" id="entry_by" name="entry_by" value="">
        </div>
        <button name="submit" type="submit">Submit</button>
    </form>
</div>