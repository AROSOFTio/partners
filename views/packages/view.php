<div class="max-w-4xl mx-auto px-4 py-12">
    <p class="badge mb-4">Package</p>
    <div class="bg-white rounded-xl border border-slate-200 card-shadow p-6 space-y-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#152228]"><?= e($package['name']) ?></h1>
                <p class="text-slate-600 mt-2"><?= e($package['full_description']) ?></p>
            </div>
            <div class="text-right">
                <div class="text-sm text-slate-500">Starting at</div>
                <div class="text-3xl font-bold text-[#05C069]"><?= format_money($package['base_price'], $package['currency']) ?></div>
                <div class="text-xs text-slate-500">Duration: <?= e($package['duration_minutes']) ?> mins</div>
            </div>
        </div>
        <div class="flex flex-wrap gap-3 text-sm">
            <span class="badge">Delivery: <?= e($package['delivery_time_text']) ?></span>
            <?php if ($package['allow_deposit']): ?>
                <span class="badge">Deposit option (<?= e($package['deposit_percentage']) ?>%)</span>
            <?php else: ?>
                <span class="badge" style="background: rgba(21,34,40,0.1); color: #152228;">Full payment</span>
            <?php endif; ?>
        </div>
        <form action="/request" method="get" class="flex items-center gap-4 mt-6">
            <input type="hidden" name="packages[]" value="<?= e($package['id']) ?>">
            <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Request Collaboration</button>
            <a href="/packages" class="text-[#152228] font-semibold">Back to packages</a>
        </form>
    </div>
</div>
