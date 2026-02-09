<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Sri Sai Mission') ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #1D0427; color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { text-align: center; padding: 40px; }
        h1 { font-size: 2.5rem; color: #9FA73E; margin-bottom: 10px; }
        h2 { font-size: 1.2rem; color: #724D67; font-weight: normal; margin-bottom: 30px; }
        .status { background: rgba(159,167,62,0.15); border: 1px solid #9FA73E; border-radius: 8px; padding: 20px 40px; display: inline-block; }
        .status p { color: #ccc; margin: 5px 0; }
        .status .ok { color: #9FA73E; font-weight: bold; }
        a { color: #5F2C70; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sri Sai Mission</h1>
        <h2>Religious & Charitable Trust (Regd) 106/2014</h2>
        <div class="status">
            <p class="ok">Backend is running</p>
            <p>PHP <?= PHP_VERSION ?> | <?= php_uname('s') ?></p>
            <p>Template integration will replace this page in Phase 8</p>
        </div>
    </div>
</body>
</html>
