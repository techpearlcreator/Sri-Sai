<!-- Razorpay SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('pooja.title') ?></h1>
        <p><?= __('pooja.subtitle') ?></p>
    </div>
</div>

<!-- Pooja Booking -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-auth-wrap" style="max-width: 700px;">

            <!-- Success panel (hidden until payment verified) -->
            <div id="poojaSuccess" style="display:none; text-align:center; padding:40px 20px;">
                <div style="font-size:64px; margin-bottom:16px;">🙏</div>
                <h2 style="color:#1D0427; margin-bottom:8px;"><?= __('pooja.success_title') ?></h2>
                <p id="poojaSuccessMsg" style="color:#555; margin-bottom:24px;"></p>
                <a href="<?= $baseUrl ?>/pooja-booking" class="srisai-btn srisai-btn--primary"><?= __('pooja.book_another') ?></a>
            </div>

            <!-- Booking form wrapper -->
            <div id="poojaFormWrap">

                <!-- Temple Selection -->
                <div class="srisai-temple-selector">
                    <h3><?= __('pooja.select_temple') ?></h3>
                    <div class="srisai-temple-cards">
                        <label class="srisai-temple-card" id="temple-perungalathur">
                            <input type="radio" name="temple" value="perungalathur" checked>
                            <div class="srisai-temple-card__content">
                                <h4><?= __('pooja.temple1_name') ?></h4>
                                <p><?= __('pooja.temple1_desc') ?></p>
                            </div>
                        </label>
                        <label class="srisai-temple-card" id="temple-keerapakkam">
                            <input type="radio" name="temple" value="keerapakkam">
                            <div class="srisai-temple-card__content">
                                <h4><?= __('pooja.temple2_name') ?></h4>
                                <p><?= __('pooja.temple2_desc') ?></p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Pooja Type Selection -->
                <div class="srisai-form-group" style="margin-top: 24px;">
                    <label for="pooja_type_id"><?= __('pooja.select_pooja') ?> <span style="color:red;">*</span></label>
                    <select id="pooja_type_id" required>
                        <option value=""><?= __('pooja.select_placeholder') ?></option>
                        <?php foreach ($poojaTypes as $pt): ?>
                            <option value="<?= (int) $pt->id ?>"
                                    data-temple="<?= htmlspecialchars($pt->temple) ?>"
                                    data-price="<?= (float) $pt->price ?>"
                                    data-duration="<?= htmlspecialchars($pt->duration ?? '') ?>"
                                    data-name="<?= htmlspecialchars($pt->name) ?>">
                                <?= htmlspecialchars($pt->name) ?> — ₹<?= number_format($pt->price, 2) ?>
                                <?= $pt->duration ? " ({$pt->duration})" : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Price Summary (hidden until pooja selected) -->
                <div id="poojaPriceSummary" style="display:none; background:#f9f5ff; border:1px solid #e0d0f0; border-radius:8px; padding:14px 18px; margin:8px 0 16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#5F2C70; font-weight:600;" id="poojaSelectedName">—</span>
                        <span style="font-size:1.2rem; font-weight:700; color:#1D0427;">₹<span id="poojaDisplayPrice">0.00</span></span>
                    </div>
                    <div style="font-size:13px; color:#724D67; margin-top:4px;" id="poojaDuration"></div>
                </div>

                <div id="poojaAlert" class="alert" style="display:none; margin-bottom:12px;"></div>

                <!-- Booking Form -->
                <form id="poojaBookingForm">
                    <div class="srisai-form-row">
                        <div class="srisai-form-group">
                            <label for="pb_name"><?= __('label.name') ?> <span style="color:red;">*</span></label>
                            <input type="text" id="pb_name" required placeholder="<?= __('label.fullname_ph') ?>">
                        </div>
                        <div class="srisai-form-group">
                            <label for="pb_phone"><?= __('label.phone') ?> <span style="color:red;">*</span></label>
                            <input type="tel" id="pb_phone" required placeholder="<?= __('label.mobile_ph') ?>">
                        </div>
                    </div>

                    <div class="srisai-form-row">
                        <div class="srisai-form-group">
                            <label for="pb_email"><?= __('label.email') ?></label>
                            <input type="email" id="pb_email" placeholder="<?= __('text.optional') ?>">
                        </div>
                        <div class="srisai-form-group">
                            <label for="pb_date"><?= __('pooja.preferred_date') ?> <span style="color:red;">*</span></label>
                            <input type="date" id="pb_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                    </div>

                    <div class="srisai-form-row">
                        <div class="srisai-form-group">
                            <label for="pb_persons"><?= __('pooja.num_persons') ?> <span style="color:red;">*</span></label>
                            <input type="number" id="pb_persons" value="1" min="1" max="50" required>
                        </div>
                        <div class="srisai-form-group" style="flex:2;">
                            <label for="pb_notes"><?= __('pooja.notes') ?></label>
                            <input type="text" id="pb_notes" placeholder="<?= __('pooja.notes_placeholder') ?>">
                        </div>
                    </div>

                    <button type="submit" class="srisai-btn srisai-btn--primary srisai-btn--full" id="poojaBookBtn" style="margin-top:8px;">
                        <?= __('pooja.pay_book') ?>
                    </button>
                </form>

                <p style="margin-top:16px; text-align:center; color:#666; font-size:13px;">
                    🔒 <?= __('pooja.secure_payment') ?>
                </p>
                <p style="text-align:center; margin-top:8px;">
                    <a href="<?= $baseUrl ?>/shop" style="color:#5F2C70; font-size:13px;"><?= __('pooja.back') ?></a>
                </p>

            </div><!-- /poojaFormWrap -->
        </div>
    </div>
</div>

<script>
(function () {
    var baseUrl = '<?= $baseUrl ?>';

    // ---- Temple & pooja type filter ----
    function filterPoojaTypes() {
        var temple  = document.querySelector('input[name="temple"]:checked').value;
        var select  = document.getElementById('pooja_type_id');
        var options = select.querySelectorAll('option[data-temple]');
        select.value = '';
        options.forEach(function (opt) {
            var t = opt.getAttribute('data-temple');
            opt.style.display = (t === temple || t === 'both') ? '' : 'none';
        });
        updatePriceSummary();
    }

    function updatePriceSummary() {
        var select  = document.getElementById('pooja_type_id');
        var summary = document.getElementById('poojaPriceSummary');
        var opt     = select.options[select.selectedIndex];
        if (!opt || !opt.value) { summary.style.display = 'none'; return; }
        var price    = parseFloat(opt.getAttribute('data-price') || 0);
        var duration = opt.getAttribute('data-duration') || '';
        var name     = opt.getAttribute('data-name') || '';
        document.getElementById('poojaSelectedName').textContent = name;
        document.getElementById('poojaDisplayPrice').textContent = price.toFixed(2);
        document.getElementById('poojaDuration').textContent     = duration ? ('Duration: ' + duration) : '';
        summary.style.display = 'block';
    }

    document.querySelectorAll('input[name="temple"]').forEach(function (r) {
        r.addEventListener('change', filterPoojaTypes);
    });
    document.getElementById('pooja_type_id').addEventListener('change', updatePriceSummary);
    filterPoojaTypes();

    // ---- Form submit → Razorpay ----
    document.getElementById('poojaBookingForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var poojaTypeId = document.getElementById('pooja_type_id').value;
        var alertEl     = document.getElementById('poojaAlert');

        function showAlert(msg, type) {
            alertEl.className = 'alert alert-' + type;
            alertEl.textContent = msg;
            alertEl.style.display = 'block';
        }

        if (!poojaTypeId) {
            showAlert('<?= __('pooja.select_pooja_error') ?>', 'error');
            return;
        }

        var btn = document.getElementById('poojaBookBtn');
        btn.disabled = true;
        btn.textContent = '<?= __('text.loading') ?>';
        alertEl.style.display = 'none';

        var payload = {
            temple:         document.querySelector('input[name="temple"]:checked').value,
            pooja_type_id:  parseInt(poojaTypeId),
            name:           document.getElementById('pb_name').value.trim(),
            phone:          document.getElementById('pb_phone').value.trim(),
            email:          document.getElementById('pb_email').value.trim(),
            preferred_date: document.getElementById('pb_date').value,
            num_persons:    parseInt(document.getElementById('pb_persons').value) || 1,
            notes:          document.getElementById('pb_notes').value.trim(),
        };

        // Step 1: Create Razorpay order
        fetch(baseUrl + '/pooja-booking/create-order', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.error) {
                showAlert(data.error, 'error');
                btn.disabled = false;
                btn.textContent = '<?= __('pooja.pay_book') ?>';
                return;
            }

            // Step 2: Open Razorpay modal
            var options = {
                key:         data.key,
                amount:      Math.round(data.amount * 100),
                currency:    data.currency,
                order_id:    data.order_id,
                name:        data.name,
                description: data.description,
                prefill:     data.prefill,
                theme:       { color: '#5F2C70' },
                handler: function (response) {
                    // Step 3: Verify payment
                    fetch(baseUrl + '/pooja-booking/verify', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            razorpay_order_id:   response.razorpay_order_id,
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_signature:  response.razorpay_signature,
                            booking_id:          data.booking_id,
                        })
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (result) {
                        if (result.success) {
                            document.getElementById('poojaFormWrap').style.display = 'none';
                            document.getElementById('poojaSuccessMsg').textContent = result.message;
                            document.getElementById('poojaSuccess').style.display = 'block';
                        } else {
                            showAlert(result.error || '<?= __('text.error') ?>', 'error');
                            btn.disabled = false;
                            btn.textContent = '<?= __('pooja.pay_book') ?>';
                        }
                    })
                    .catch(function () {
                        showAlert('<?= __('text.error') ?>', 'error');
                        btn.disabled = false;
                        btn.textContent = '<?= __('pooja.pay_book') ?>';
                    });
                },
                modal: {
                    ondismiss: function () {
                        btn.disabled = false;
                        btn.textContent = '<?= __('pooja.pay_book') ?>';
                    }
                }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        })
        .catch(function () {
            showAlert('<?= __('text.error') ?>', 'error');
            btn.disabled = false;
            btn.textContent = '<?= __('pooja.pay_book') ?>';
        });
    });
})();
</script>
