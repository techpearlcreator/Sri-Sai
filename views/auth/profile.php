<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('profile.title') ?></h1>
        <p><?= __('profile.welcome') ?> <?= htmlspecialchars($user->name) ?></p>
    </div>
</div>

<!-- Profile Content -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-profile-grid">
            <!-- Profile Info -->
            <div class="srisai-auth-form">
                <h3><?= __('profile.info') ?></h3>
                <div id="profileAlert" class="alert" style="display:none;"></div>

                <form id="profileForm">
                    <div class="srisai-form-group">
                        <label for="name"><?= __('profile.name') ?> <span style="color:red;">*</span></label>
                        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user->name) ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label><?= __('profile.email') ?></label>
                        <input type="email" value="<?= htmlspecialchars($user->email) ?>" disabled>
                    </div>

                    <div class="srisai-form-group">
                        <label><?= __('profile.phone') ?></label>
                        <input type="tel" value="<?= htmlspecialchars($user->phone) ?>" disabled>
                    </div>

                    <hr style="margin:20px 0;">
                    <h4><?= __('profile.change_password') ?></h4>

                    <div class="srisai-form-group">
                        <label for="current_password"><?= __('profile.current_password') ?></label>
                        <input type="password" id="current_password" name="current_password" placeholder="<?= __('profile.current_pw_ph') ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label for="new_password"><?= __('profile.new_password') ?></label>
                        <input type="password" id="new_password" name="new_password" placeholder="<?= __('profile.min_chars_ph') ?>">
                    </div>

                    <button type="submit" class="srisai-btn srisai-btn--primary" id="profileBtn"><?= __('profile.update_btn') ?></button>
                </form>
            </div>

            <!-- Tour Bookings -->
            <div>
                <h3><?= __('profile.my_bookings') ?></h3>
                <?php if (empty($bookings)): ?>
                    <div class="srisai-empty">
                        <p><?= __('profile.no_bookings') ?></p>
                        <a href="<?= $baseUrl ?>/tours" class="srisai-btn srisai-btn--outline"><?= __('profile.browse_tours') ?></a>
                    </div>
                <?php else: ?>
                    <div class="srisai-bookings-list">
                        <?php foreach ($bookings as $booking): ?>
                            <div class="srisai-booking-card">
                                <div class="srisai-booking-card__header">
                                    <h4><?= htmlspecialchars($booking->tour_title) ?></h4>
                                    <span class="srisai-badge srisai-badge--<?= $booking->payment_status ?>">
                                        <?= __('payment.' . $booking->payment_status) ?>
                                    </span>
                                </div>
                                <div class="srisai-booking-card__details">
                                    <p><strong><?= __('tours.destination') ?>:</strong> <?= htmlspecialchars($booking->destination) ?></p>
                                    <p><strong><?= __('label.date') ?>:</strong> <?= date('M d, Y', strtotime($booking->start_date)) ?> - <?= date('M d, Y', strtotime($booking->end_date)) ?></p>
                                    <p><strong><?= __('tours.seats') ?>:</strong> <?= (int) $booking->seats ?></p>
                                    <p><strong><?= __('profile.amount') ?>:</strong> Rs.<?= number_format($booking->total_amount, 2) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('profileBtn');
    const alertEl = document.getElementById('profileAlert');
    btn.disabled = true;
    btn.textContent = '<?= __('text.loading') ?>';

    fetch('<?= $baseUrl ?>/profile/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name: document.getElementById('name').value,
            current_password: document.getElementById('current_password').value,
            new_password: document.getElementById('new_password').value
        })
    })
    .then(r => r.json())
    .then(data => {
        alertEl.style.display = 'block';
        if (data.success) {
            alertEl.className = 'alert alert-success';
            alertEl.textContent = data.message;
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
        } else {
            alertEl.className = 'alert alert-error';
            alertEl.textContent = data.error;
        }
        btn.disabled = false;
        btn.textContent = '<?= __('profile.update_btn') ?>';
    })
    .catch(() => {
        alertEl.className = 'alert alert-error';
        alertEl.textContent = '<?= __('text.error_short') ?>';
        alertEl.style.display = 'block';
        btn.disabled = false;
        btn.textContent = '<?= __('profile.update_btn') ?>';
    });
});
</script>
