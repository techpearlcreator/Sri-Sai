<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= htmlspecialchars(langField($tour, 'title')) ?></h1>
        <p><a href="<?= $baseUrl ?>/tours"><?= __('tours.back') ?></a></p>
    </div>
</div>

<!-- Tour Detail -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-tour-detail">
            <!-- Main Content -->
            <div class="srisai-tour-detail__main">
                <?php if (!empty($tour->featured_image)): ?>
                    <div class="srisai-tour-detail__image">
                        <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($tour->featured_image) ?>" alt="<?= htmlspecialchars(langField($tour, 'title')) ?>">
                    </div>
                <?php endif; ?>

                <div class="srisai-tour-detail__info-grid">
                    <div class="srisai-tour-info-item">
                        <strong><?= __('tours.destination') ?></strong>
                        <span><?= htmlspecialchars(langField($tour, 'destination')) ?></span>
                    </div>
                    <div class="srisai-tour-info-item">
                        <strong><?= __('tours.start_date') ?></strong>
                        <span><?= date('F d, Y', strtotime($tour->start_date)) ?></span>
                    </div>
                    <div class="srisai-tour-info-item">
                        <strong><?= __('tours.end_date') ?></strong>
                        <span><?= date('F d, Y', strtotime($tour->end_date)) ?></span>
                    </div>
                    <div class="srisai-tour-info-item">
                        <strong><?= __('tours.status') ?></strong>
                        <span class="srisai-badge srisai-badge--<?= $tour->status ?>"><?= __('tours.status_' . $tour->status) ?></span>
                    </div>
                </div>

                <?php if (!empty($tour->description)): ?>
                    <div class="srisai-tour-detail__desc">
                        <?= nl2br(htmlspecialchars(langField($tour, 'description'))) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Booking Sidebar -->
            <div class="srisai-tour-sidebar">
                <div class="srisai-tour-sidebar__card">
                    <div class="srisai-tour-sidebar__price">
                        Rs.<?= number_format($tour->price_per_person, 2) ?>
                        <small><?= __('tours.price_per_person') ?></small>
                    </div>

                    <div class="srisai-tour-sidebar__seats">
                        <span class="srisai-tour-sidebar__seats-left"><?= $seatsLeft ?></span> <?= __('tours.available') ?>
                        <div class="srisai-progress-bar">
                            <div class="srisai-progress-bar__fill" style="width: <?= ((int)$tour->booked_seats / max(1, (int)$tour->max_seats)) * 100 ?>%"></div>
                        </div>
                    </div>

                    <?php if ($seatsLeft > 0 && $tour->status === 'upcoming'): ?>
                        <?php if ($loggedIn): ?>
                            <div id="tourBookingAlert" class="alert" style="display:none;"></div>

                            <div class="srisai-form-group">
                                <label for="booking_seats"><?= __('tours.seats') ?></label>
                                <input type="number" id="booking_seats" value="1" min="1" max="<?= $seatsLeft ?>">
                            </div>

                            <div class="srisai-tour-sidebar__total">
                                <?= __('tours.total') ?> <strong id="totalAmount">Rs.<?= number_format($tour->price_per_person, 2) ?></strong>
                            </div>

                            <button class="srisai-btn srisai-btn--primary srisai-btn--full" id="bookTourBtn" onclick="bookTour()">
                                <?= __('tours.book_pay') ?>
                            </button>
                        <?php else: ?>
                            <div class="srisai-tour-sidebar__login-prompt">
                                <p><?= __('tours.login_prompt') ?></p>
                                <a href="<?= $baseUrl ?>/login" class="srisai-btn srisai-btn--primary srisai-btn--full"><?= __('tours.login_to_book') ?></a>
                                <p style="margin-top:8px;"><small><?= __('tours.no_account') ?> <a href="<?= $baseUrl ?>/register"><?= __('nav.register') ?></a></small></p>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($tour->status !== 'upcoming'): ?>
                        <div class="srisai-tour-sidebar__closed">
                            <p><?= __('tours.status_is') ?> <?= __('tours.status_' . $tour->status) ?></p>
                        </div>
                    <?php else: ?>
                        <div class="srisai-tour-sidebar__closed">
                            <p><?= __('tours.fully_booked_msg') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($loggedIn && $seatsLeft > 0 && $tour->status === 'upcoming'): ?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var pricePerPerson = <?= (float) $tour->price_per_person ?>;
var seatsInput = document.getElementById('booking_seats');
var totalDisplay = document.getElementById('totalAmount');

seatsInput.addEventListener('input', function() {
    var seats = parseInt(this.value) || 1;
    totalDisplay.textContent = 'Rs.' + (seats * pricePerPerson).toFixed(2);
});

function bookTour() {
    var btn = document.getElementById('bookTourBtn');
    var alertEl = document.getElementById('tourBookingAlert');
    var seats = parseInt(seatsInput.value) || 1;

    btn.disabled = true;
    btn.textContent = '<?= __('text.loading') ?>';
    alertEl.style.display = 'none';

    fetch('<?= $baseUrl ?>/tours/create-order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ tour_id: <?= (int) $tour->id ?>, seats: seats })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.error) {
            alertEl.className = 'alert alert-error';
            alertEl.textContent = data.error;
            alertEl.style.display = 'block';
            btn.disabled = false;
            btn.textContent = '<?= __('tours.book_pay') ?>';
            return;
        }

        var options = {
            key: data.key,
            amount: data.amount * 100,
            currency: data.currency,
            name: data.name,
            description: data.description,
            order_id: data.order_id,
            prefill: {
                name: '<?= htmlspecialchars($currentUser['name'] ?? '') ?>',
                email: '<?= htmlspecialchars($currentUser['email'] ?? '') ?>',
                contact: '<?= htmlspecialchars($currentUser['phone'] ?? '') ?>'
            },
            handler: function(response) {
                btn.textContent = '<?= __('text.verifying') ?>';

                fetch('<?= $baseUrl ?>/tours/verify', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature,
                        booking_id: data.booking_id
                    })
                })
                .then(function(r) { return r.json(); })
                .then(function(verifyData) {
                    alertEl.style.display = 'block';
                    if (verifyData.success) {
                        alertEl.className = 'alert alert-success';
                        alertEl.textContent = verifyData.message;
                        btn.style.display = 'none';
                    } else {
                        alertEl.className = 'alert alert-error';
                        alertEl.textContent = verifyData.error || '<?= __('text.verification_failed') ?>';
                        btn.disabled = false;
                        btn.textContent = '<?= __('tours.book_pay') ?>';
                    }
                });
            },
            modal: {
                ondismiss: function() {
                    btn.disabled = false;
                    btn.textContent = '<?= __('tours.book_pay') ?>';
                }
            },
            theme: { color: '#5F2C70' }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(function() {
        alertEl.className = 'alert alert-error';
        alertEl.textContent = '<?= __('text.error') ?>';
        alertEl.style.display = 'block';
        btn.disabled = false;
        btn.textContent = '<?= __('tours.book_pay') ?>';
    });
}
</script>
<?php endif; ?>
