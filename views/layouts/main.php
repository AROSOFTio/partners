<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(config_value('app_name', 'BenTech Collaborations')) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-[#f7f7f5] text-[#101316]" style="font-family: 'Manrope', sans-serif;">
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="mx-auto w-[92vw] max-w-[1100px] lg:w-[68vw] flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 py-4">
            <a href="/" class="flex flex-col">
                <span class="font-display text-lg font-semibold">BenTech Collaborations</span>
                <span class="text-xs text-slate-500">Powered by AROSOFT Innovations Ltd</span>
            </a>
            <div class="flex flex-wrap items-center gap-3">
                <nav class="flex flex-wrap items-center gap-4 text-sm font-medium text-slate-700">
                    <a href="/" class="hover:text-[#ff0033]">Home</a>
                    <a href="/packages/" class="hover:text-[#ff0033]">Packages</a>
                    <a href="/portfolio/" class="hover:text-[#ff0033]">Portfolio</a>
                    <a href="/request/" class="rounded-full bg-[#ff0033] px-4 py-2 text-white shadow hover:bg-[#d9002c]">Request Collab</a>
                </nav>
                <?php
                $currencyRates = config_value('currency.rates', []);
                $currentCurrency = get_display_currency();
                ?>
                <form method="get" action="<?= e(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/') ?>">
                    <label class="sr-only" for="currency-switch">Currency</label>
                    <select id="currency-switch" name="currency" class="rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700" onchange="this.form.submit()">
                        <?php foreach ($currencyRates as $code => $rate): ?>
                            <option value="<?= e($code) ?>" <?= $code === $currentCurrency ? 'selected' : '' ?>><?= e($code) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
    </header>

    <main class="min-h-[70vh]">
        <?= $content ?? '' ?>
    </main>

    <footer class="mt-16 bg-[#0d0f12] text-slate-300">
        <div class="mx-auto w-[92vw] max-w-[1100px] lg:w-[68vw] py-8 text-sm flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>© <?= date('Y') ?> BenTech. Powered & billed by AROSOFT Innovations Ltd.</div>
            <div class="text-slate-400">Payments are securely processed via Pesapal.</div>
        </div>
    </footer>
</body>
</html>
