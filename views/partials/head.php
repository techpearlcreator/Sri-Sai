<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="theme-color" content="#1D0427">
<meta name="format-detection" content="telephone=yes">
<title><?= htmlspecialchars($pageTitle ?? 'Sri Sai Mission') ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDescription ?? __('head.description')) ?>">

<!-- Favicon -->
<link rel="icon" type="image/jpeg" sizes="32x32" href="<?= $assetUrl ?>/images/cropped-favicon-32x32.jpg">
<link rel="apple-touch-icon" sizes="180x180" href="<?= $assetUrl ?>/images/cropped-favicon-180x180.jpg">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&family=Kumbh+Sans:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<!-- Icon Fonts -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/fontello.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/trx_addons_icons.css">

<!-- Core CSS -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/swiper-bundle.min.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/magnific-popup.min.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/odometer.min.css">

<!-- Sri Sai Mission Custom Design System -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/srisai-custom.css?v=<?= time() ?>">

<!-- Razorpay Checkout -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
