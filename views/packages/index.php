<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="badge mb-2">BenTech Packages</p>
            <h1 class="text-3xl font-bold text-[#152228]">YouTube collaboration packages</h1>
            <p class="text-slate-600">Pick what fits your campaign goals. Mix and match for broader reach.</p>
        </div>
        <a href="/request" class="px-5 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full">Request Collaboration</a>
    </div>

    <form action="/request" method="get" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($packages as $pkg): ?>
            <label class="block bg-white rounded-xl p-6 border border-slate-200 card-shadow cursor-pointer hover:-translate-y-1 transition transform">
                <div class="flex items-start gap-3">
                    <input type="checkbox" name="packages[]" value="<?= e($pkg['id']) ?>" class="mt-1 h-5 w-5 text-[#05C069] border-slate-300 rounded">
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-[#152228]"><a href="/package?slug=<?= e($pkg['slug']) ?>" class="hover:text-[#05C069]"><?= e($pkg['name']) ?></a></h2>
                            <span class="text-[#05C069] font-bold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></span>
                        </div>
                        <p class="text-slate-600 text-sm"><?= e($pkg['short_description']) ?></p>
                        <p class="text-slate-500 text-xs">Delivery: <?= e($pkg['delivery_time_text']) ?> | Duration: <?= e($pkg['duration_minutes']) ?> mins</p>
                        <?php if ($pkg['allow_deposit']): ?>
                            <span class="badge">Deposit available (<?= e($pkg['deposit_percentage']) ?>%)</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(21,34,40,0.1); color: #152228;">Full payment</span>
                        <?php endif; ?>
                    </div>
                </div>
            </label>
        <?php endforeach; ?>
        <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Request Collaboration</button>
        </div>
    </form>
</div>
