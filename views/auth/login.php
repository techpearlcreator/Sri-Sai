<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('login.title') ?></h1>
        <p><?= __('login.subtitle') ?></p>
    </div>
</div>

<!-- Login Form -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-auth-wrap">
            <div class="srisai-auth-form">
                <h3><?= __('login.welcome') ?></h3>
                <p class="srisai-auth-subtitle"><?= __('login.prompt') ?></p>

                <div id="loginAlert" class="alert alert-error" style="display:none;"></div>

                <form id="loginForm">
                    <div class="srisai-form-group">
                        <label for="credential"><?= __('login.credential') ?> <span style="color:red;">*</span></label>
                        <input type="text" id="credential" name="credential" required placeholder="<?= __('login.credential_ph') ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label for="password"><?= __('login.password') ?> <span style="color:red;">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="<?= __('login.password_ph') ?>">
                    </div>

                    <button type="submit" class="srisai-btn srisai-btn--primary srisai-btn--full" id="loginBtn"><?= __('login.btn') ?></button>
                </form>

                <p class="srisai-auth-footer">
                    <?= __('login.no_account') ?> <a href="<?= $baseUrl ?>/register"><?= __('login.register') ?></a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('loginBtn');
    const alertEl = document.getElementById('loginAlert');
    btn.disabled = true;
    btn.textContent = '<?= __('text.loading') ?>';
    alertEl.style.display = 'none';

    fetch('<?= $baseUrl ?>/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            credential: document.getElementById('credential').value,
            password: document.getElementById('password').value
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
            btn.textContent = '<?= __('login.btn') ?>';
        }
    })
    .catch(() => {
        alertEl.textContent = '<?= __('text.error') ?>';
        alertEl.style.display = 'block';
        btn.disabled = false;
        btn.textContent = '<?= __('login.btn') ?>';
    });
});
</script>
