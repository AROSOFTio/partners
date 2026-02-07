<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(config_value('app_name', 'BenTech Collaborations')) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="app-body">
    <header class="site-header">
        <div class="container header-inner">
            <div class="brand">
                <div class="brand-title">BenTech Collaborations</div>
                <div class="brand-sub">Powered by AROSOFT Innovations Ltd</div>
            </div>
            <div class="header-actions">
                <nav class="nav">
                    <a href="/" class="nav-link">Home</a>
                    <a href="/packages/" class="nav-link">Packages</a>
                    <a href="/portfolio/" class="nav-link">Portfolio</a>
                    <a href="/request/" class="nav-cta">Request Collab</a>
                </nav>
                <?php
                $currencyRates = config_value('currency.rates', []);
                $currentCurrency = get_display_currency();
                ?>
                <form method="get" action="<?= e(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/') ?>">
                    <label class="sr-only" for="currency-switch">Currency</label>
                    <select id="currency-switch" name="currency" class="currency-select" onchange="this.form.submit()">
                        <?php foreach ($currencyRates as $code => $rate): ?>
                            <option value="<?= e($code) ?>" <?= $code === $currentCurrency ? 'selected' : '' ?>><?= e($code) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        <?= $content ?? '' ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <div>(c) <?= date('Y') ?> BenTech. Powered & billed by AROSOFT Innovations Ltd.</div>
            <div class="footer-note">Payments are securely processed via Pesapal.</div>
        </div>
    </footer>
</body>
</html>
