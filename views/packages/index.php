<?php
$videoPackages = $packageGroups['video'] ?? [];
$comboPackages = $packageGroups['combo'] ?? [];
$popularById = [];

foreach ($popularPackages as $popularPackage) {
    $popularById[(int)$popularPackage['id']] = true;
}

$buildWhatsAppLink = static function (array $package) use ($whatsappNumber): string {
    $message = rawurlencode('Hello BenTech, I need more details about "' . ($package['name'] ?? 'this package') . '".');
    if (!empty($whatsappNumber)) {
        return 'https://wa.me/' . $whatsappNumber . '?text=' . $message;
    }
    return 'https://wa.me/?text=' . $message;
};
?>

<div class="max-w-6xl mx-auto px-4 py-12 space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <p class="badge mb-2">BenTech Packages</p>
            <h1 class="text-3xl font-bold text-[#152228]">Collaboration packages by type</h1>
            <p class="text-slate-600">Choose from Video Packages and Combo Packages. Popular tags use real request counts.</p>
        </div>
        <a href="/request" class="px-5 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full">Request Collaboration</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 card-shadow">
        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Popular picks &#128293;</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach (array_slice($popularPackages, 0, 4) as $pkg): ?>
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-[#152228]"><?= e($pkg['name']) ?></h2>
                            <p class="text-xs text-slate-500 mt-1"><?= (int)$pkg['request_count'] ?> requests</p>
                        </div>
                        <span class="text-[#05C069] font-bold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <form action="/request/" method="get" class="space-y-10">
        <section>
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-[#152228]">Video Packages</h2>
                <p class="text-sm text-slate-600">Single-format video collaborations.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($videoPackages as $pkg): ?>
                    <div class="bg-white rounded-xl p-6 border border-slate-200 card-shadow">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="packages[]" value="<?= e($pkg['id']) ?>" class="mt-1 h-5 w-5 text-[#05C069] border-slate-300 rounded">
                            <div class="flex-1 space-y-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-xl font-semibold text-[#152228]">
                                                <a href="/package?slug=<?= e($pkg['slug']) ?>" class="hover:text-[#05C069]"><?= e($pkg['name']) ?></a>
                                            </h3>
                                            <?php if (!empty($popularById[(int)$pkg['id']])): ?>
                                                <span class="badge">Popular &#128293;</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-slate-600 text-sm"><?= e($pkg['short_description']) ?></p>
                                    </div>
                                    <span class="text-[#05C069] font-bold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></span>
                                </div>
                                <p class="text-slate-500 text-xs">Delivery: <?= e($pkg['delivery_time_text']) ?> | Duration: <?= e($pkg['duration_minutes']) ?> mins</p>
                                <p class="text-slate-500 text-xs">Requested <?= (int)$pkg['request_count'] ?> times</p>
                                <?php if ($pkg['allow_deposit']): ?>
                                    <span class="badge">Deposit available (<?= e($pkg['deposit_percentage']) ?>%)</span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(21,34,40,0.1); color: #152228;">Full payment</span>
                                <?php endif; ?>
                                <div class="pt-2">
                                    <a href="<?= e($buildWhatsAppLink($pkg)) ?>" target="_blank" rel="noopener" class="text-sm font-semibold text-[#128c7e]">Chat on WhatsApp for details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($videoPackages)): ?>
                    <div class="md:col-span-2 rounded-xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500">
                        No video packages yet. Add one from admin packages.
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-[#152228]">Combo Packages</h2>
                <p class="text-sm text-slate-600">Bundled offers that combine multiple deliverables.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($comboPackages as $pkg): ?>
                    <div class="bg-white rounded-xl p-6 border border-slate-200 card-shadow">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="packages[]" value="<?= e($pkg['id']) ?>" class="mt-1 h-5 w-5 text-[#05C069] border-slate-300 rounded">
                            <div class="flex-1 space-y-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-xl font-semibold text-[#152228]">
                                                <a href="/package?slug=<?= e($pkg['slug']) ?>" class="hover:text-[#05C069]"><?= e($pkg['name']) ?></a>
                                            </h3>
                                            <?php if (!empty($popularById[(int)$pkg['id']])): ?>
                                                <span class="badge">Popular &#128293;</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-slate-600 text-sm"><?= e($pkg['short_description']) ?></p>
                                    </div>
                                    <span class="text-[#05C069] font-bold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></span>
                                </div>
                                <p class="text-slate-500 text-xs">Delivery: <?= e($pkg['delivery_time_text']) ?> | Duration: <?= e($pkg['duration_minutes']) ?> mins</p>
                                <p class="text-slate-500 text-xs">Requested <?= (int)$pkg['request_count'] ?> times</p>
                                <?php if ($pkg['allow_deposit']): ?>
                                    <span class="badge">Deposit available (<?= e($pkg['deposit_percentage']) ?>%)</span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(21,34,40,0.1); color: #152228;">Full payment</span>
                                <?php endif; ?>
                                <div class="pt-2">
                                    <a href="<?= e($buildWhatsAppLink($pkg)) ?>" target="_blank" rel="noopener" class="text-sm font-semibold text-[#128c7e]">Chat on WhatsApp for details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($comboPackages)): ?>
                    <div class="md:col-span-2 rounded-xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500">
                        No combo packages yet. Add a category with "combo" in the name and assign packages.
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Request Selected Packages</button>
        </div>
    </form>
</div>
