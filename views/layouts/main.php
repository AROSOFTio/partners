<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(config_value('app_name', 'BenTech Collaborations')) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-slate-50 text-slate-900" style="font-family: 'Poppins', sans-serif;">
    <header class="bg-[#152228] text-white shadow">
        <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <div class="text-2xl font-bold">BenTech Collaborations</div>
                <div class="text-sm text-emerald-300">Powered by AROSOFT Innovations Ltd</div>
            </div>
            <div class="flex items-center gap-4 flex-wrap md:justify-end">
                <nav class="space-x-4 text-sm md:text-base">
                    <a href="/" class="hover:text-emerald-300">Home</a>
                    <a href="/packages" class="hover:text-emerald-300">Packages</a>
                    <a href="/portfolio" class="hover:text-emerald-300">Portfolio</a>
                    <a href="/request" class="bg-[#05C069] text-[#152228] font-semibold px-4 py-2 rounded-full hover:opacity-90">Request Collab</a>
                </nav>
                <?php
                $currencyRates = config_value('currency.rates', []);
                $currentCurrency = get_display_currency();
                ?>
                <form method="get" action="<?= e(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/') ?>">
                    <label class="sr-only" for="currency-switch">Currency</label>
                    <select id="currency-switch" name="currency" class="text-sm text-[#152228] rounded-lg px-3 py-2 border border-emerald-200 bg-white" onchange="this.form.submit()">
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

    <footer class="bg-[#152228] text-white mt-12">
        <div class="max-w-6xl mx-auto px-4 py-6 text-sm flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>© <?= date('Y') ?> BenTech — Powered & billed by AROSOFT Innovations Ltd. All rights reserved.</div>
            <div class="text-emerald-300">Payments are securely processed via Pesapal.</div>
        </div>
    </footer>
</body>
</html>
