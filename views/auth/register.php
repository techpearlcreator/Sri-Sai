<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('register.title') ?></h1>
        <p><?= __('register.subtitle') ?></p>
    </div>
</div>

<!-- Register Form -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-auth-wrap">
            <div class="srisai-auth-form">
                <h3><?= __('register.heading') ?></h3>
                <p class="srisai-auth-subtitle"><?= __('register.prompt') ?></p>

                <div id="registerAlert" class="alert alert-error" style="display:none;"></div>

                <form id="registerForm">
                    <div class="srisai-form-group">
                        <label for="name"><?= __('register.name') ?> <span style="color:red;">*</span></label>
                        <input type="text" id="name" name="name" required placeholder="<?= __('register.name_ph') ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label for="email"><?= __('register.email') ?> <span style="color:red;">*</span></label>
                        <input type="email" id="email" name="email" required placeholder="<?= __('register.email_ph') ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label for="phone"><?= __('register.phone') ?> <span style="color:red;">*</span></label>
                        <input type="tel" id="phone" name="phone" required placeholder="<?= __('register.phone_placeholder') ?>">
                    </div>

                    <div class="srisai-form-row">
                        <div class="srisai-form-group">
                            <label for="password"><?= __('register.password') ?> <span style="color:red;">*</span></label>
                            <input type="password" id="password" name="password" required placeholder="<?= __('register.password_placeholder') ?>">
                        </div>
                        <div class="srisai-form-group">
                            <label for="confirm_password"><?= __('register.confirm') ?> <span style="color:red;">*</span></label>
                            <input type="password" id="confirm_password" name="confirm_password" required placeholder="<?= __('register.confirm_ph') ?>">
                        </div>
                    </div>

                    <button type="submit" class="srisai-btn srisai-btn--primary srisai-btn--full" id="registerBtn"><?= __('register.btn') ?></button>
                </form>

                <p class="srisai-auth-footer">
                    <?= __('register.have_account') ?> <a href="<?= $baseUrl ?>/login"><?= __('register.login') ?></a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('registerBtn');
    const alertEl = document.getElementById('registerAlert');
    btn.disabled = true;
    btn.textContent = '<?= __('text.loading') ?>';
    alertEl.style.display = 'none';

    fetch('<?= $baseUrl ?>/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            password: document.getElementById('password').value,
            confirm_password: document.getElementById('confirm_password').value
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alertEl.textContent = data.error;
            alertEl.style.display = 'block';
            btn.disabled = false;
            btn.textContent = '<?= __('register.btn') ?>';
        }
    })
    .catch(() => {
        alertEl.textContent = '<?= __('text.error') ?>';
        alertEl.style.display = 'block';
        btn.disabled = false;
        btn.textContent = '<?= __('register.btn') ?>';
    });
});
</script>
