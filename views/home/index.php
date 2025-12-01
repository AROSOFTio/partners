<section class="hero-banner text-white py-16">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10">
        <div class="flex-1 space-y-6">
            <div class="badge">BenTech x YouTube Collaborations</div>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">Partner with BenTech for authentic, high-performing YouTube features.</h1>
            <p class="text-lg text-emerald-100">From full reviews to quick mentions, we create content that viewers trust. Powered and billed by AROSOFT Innovations Ltd.</p>
            <div class="flex gap-4 flex-wrap">
                <a href="/packages" class="px-6 py-3 rounded-full bg-[#05C069] text-[#152228] font-semibold hover:opacity-90">Explore Packages</a>
                <a href="/request" class="px-6 py-3 rounded-full border border-emerald-300 text-white hover:bg-white hover:text-[#152228]">Request a Custom Collab</a>
            </div>
        </div>
        <div class="flex-1 card-shadow bg-white/90 text-slate-900 rounded-2xl p-6 md:p-8 space-y-4">
            <div class="text-sm font-semibold text-[#05C069]">Popular Picks</div>
            <?php foreach (array_slice($packages, 0, 3) as $pkg): ?>
                <div class="border-b last:border-none border-slate-200 pb-4 mb-4 last:pb-0 last:mb-0">
                    <div class="flex items-center justify-between">
                        <div class="font-semibold text-lg"><?= e($pkg['name']) ?></div>
                        <div class="text-[#05C069] font-bold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></div>
                    </div>
                    <p class="text-sm text-slate-600 mt-2"><?= e($pkg['short_description']) ?></p>
                </div>
            <?php endforeach; ?>
            <a href="/packages" class="inline-block text-sm font-semibold text-[#152228]">See all packages</a>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-[#152228]">Collaboration Packages</h2>
        <p class="text-sm text-slate-600">Select one or more packages and submit your request.</p>
    </div>
    <form action="/request" method="get" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($packages as $pkg): ?>
            <label class="block bg-white rounded-xl p-6 border border-slate-200 card-shadow cursor-pointer hover:-translate-y-1 transition transform">
                <div class="flex items-start gap-3">
                    <input type="checkbox" name="packages[]" value="<?= e($pkg['id']) ?>" class="mt-1 h-5 w-5 text-[#05C069] border-slate-300 rounded">
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-[#152228]"><?= e($pkg['name']) ?></h3>
                            <span class="text-[#05C069] font-bold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></span>
                        </div>
                        <p class="text-slate-600 text-sm"><?= e($pkg['short_description']) ?></p>
                        <p class="text-slate-500 text-xs">Delivery: <?= e($pkg['delivery_time_text']) ?></p>
                        <?php if ($pkg['allow_deposit']): ?>
                            <span class="badge">Deposit available (<?= e($pkg['deposit_percentage']) ?>%)</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(21,34,40,0.1); color: #152228;">Full payment</span>
                        <?php endif; ?>
                    </div>
                </div>
            </label>
        <?php endforeach; ?>
        <div class="md:col-span-2 flex justify-between items-center">
            <p class="text-sm text-slate-600">Choose the packages that fit your campaign. You can request multiple at once.</p>
            <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Request Collaboration</button>
        </div>
    </form>
</section>

<section class="bg-white py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-[#152228]">Some of our previous collaborations</h2>
            <a href="/portfolio" class="text-[#05C069] font-semibold">View all</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($featured as $item): ?>
                <div class="bg-slate-900 text-white rounded-xl overflow-hidden card-shadow">
                    <div class="aspect-video">
                        <iframe class="w-full h-full" src="<?= e(str_replace('watch?v=', 'embed/', $item['youtube_url'])) ?>" allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div class="p-4 space-y-2">
                        <div class="text-sm text-emerald-300"><?= e($item['brand_name']) ?></div>
                        <div class="font-semibold"><?= e($item['title']) ?></div>
                        <p class="text-sm text-slate-200"><?= e($item['short_description']) ?></p>
                        <span class="text-xs uppercase tracking-wide text-emerald-200"><?= e($item['collab_type']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($featured)): ?>
                <p class="text-slate-600">No featured collaborations yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
