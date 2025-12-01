<?php
$total = 0;
$depositTotal = 0;
$depositAllowed = !empty($selectedPackages);
foreach ($selectedPackages as $pkg) {
    $total += (float)$pkg['base_price'];
    if (!empty($pkg['allow_deposit'])) {
        $depositTotal += (float)$pkg['base_price'] * ((float)$pkg['deposit_percentage'] / 100);
    } else {
        $depositAllowed = false;
    }
}
?>
<form action="/request" method="post" class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div>
            <p class="badge mb-3">Request collaboration</p>
            <h1 class="text-3xl font-bold text-[#152228]">Tell us about your campaign</h1>
            <p class="text-slate-600">Select packages on the right, then share your brief. We will confirm timelines right away.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                <ul class="list-disc ml-5 space-y-1">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Your Name *</label>
                <input type="text" name="customer_name" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Email *</label>
                <input type="email" name="customer_email" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Brand / Product name *</label>
                <input type="text" name="company_name" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Website or product URL</label>
                <input type="text" name="website_url" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="https://">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-[#152228]">Short brief / campaign details *</label>
            <textarea name="brief" rows="4" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Preferred timeline</label>
                <input type="text" name="preferred_timeline" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="e.g. 5-7 business days">
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#152228] mb-2">Payment option</label>
                <div class="space-y-2 bg-white border border-slate-200 rounded-lg p-3">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" name="payment_type" value="full" checked> <span>Full payment (100%)</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm <?= $depositAllowed ? '' : 'opacity-50 cursor-not-allowed' ?>">
                        <input type="radio" name="payment_type" value="deposit" <?= $depositAllowed ? '' : 'disabled' ?>>
                        <span>50% deposit (if eligible)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Continue to summary</button>
        </div>
    </div>

    <aside class="bg-white rounded-xl border border-slate-200 card-shadow p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-[#152228]">Select packages</h2>
            <a href="/packages" class="text-sm text-[#05C069] font-semibold">View details</a>
        </div>
        <div class="space-y-3 max-h-[420px] overflow-y-auto pr-2">
            <?php foreach ($allPackages as $pkg): ?>
                <label class="flex items-start gap-3 text-sm border border-slate-200 rounded-lg p-3">
                    <input type="checkbox" name="packages[]" value="<?= e($pkg['id']) ?>" class="mt-1 h-4 w-4 text-[#05C069]" <?= in_array($pkg['id'], $selectedIds) ? 'checked' : '' ?>>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-[#152228]"><?= e($pkg['name']) ?></span>
                            <span class="text-[#05C069] font-semibold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></span>
                        </div>
                        <p class="text-slate-600 text-xs mt-1"><?= e($pkg['short_description']) ?></p>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
        <div class="border-t border-slate-200 pt-3 text-sm space-y-1">
            <div class="flex justify-between font-semibold">
                <span>Total (100%)</span>
                <span><?= $selectedPackages ? format_money($total, $selectedPackages[0]['currency']) : 'UGX 0.00' ?></span>
            </div>
            <?php if ($depositAllowed && $selectedPackages): ?>
                <div class="flex justify-between text-[#05C069] font-semibold">
                    <span>Deposit (50% blended)</span>
                    <span><?= format_money($depositTotal, $selectedPackages[0]['currency']) ?></span>
                </div>
            <?php endif; ?>
        </div>
        <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-xs text-slate-600">
            Payments are securely processed by AROSOFT Innovations Ltd via Pesapal. You will review the summary before paying.
        </div>
    </aside>
</form>
