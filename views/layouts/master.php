<?php
/**
 * Master Layout — wraps all public pages.
 * Variables available: $__content, $pageTitle, $pageClass, $baseUrl, $assetUrl
 */
$baseUrl    = '/srisai/public';
$assetUrl   = $baseUrl . '/assets';
$currentLang = App\Helpers\Lang::current();
$htmlLang    = $currentLang === 'ta' ? 'ta' : 'en';
?>
<!DOCTYPE html>
<html lang="<?= $htmlLang ?>">
<head>
<?php require VIEWS_PATH . '/partials/head.php'; ?>
</head>
<body class="<?= htmlspecialchars($pageClass ?? 'page') ?>_body scheme_default">

<?php require VIEWS_PATH . '/partials/header.php'; ?>

<main class="page_content_wrap">
    <?= $__content ?>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>

<!-- Scroll to Top -->
<a href="#" class="scroll-to-top trx_addons_icon-up"></a>

<?php require VIEWS_PATH . '/partials/scripts.php'; ?>

</body>
</html>
