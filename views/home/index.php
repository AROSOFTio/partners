<section class="hero-banner text-white py-20">
    <div class="container flex flex-col lg:flex-row items-center gap-12">
        <div class="flex-1 space-y-6">
            <div class="badge">Strategic creator partnerships</div>
            <h1 class="text-4xl md:text-5xl font-semibold leading-tight" style="font-family: 'Newsreader', serif;">
                Premium YouTube collaborations that feel natural and convert.
            </h1>
            <p class="text-base md:text-lg text-emerald-100 max-w-xl">
                Work with BenTech to deliver smart, brand-safe integrations. Clear packages, fast turnaround, and a frictionless checkout.
            </p>
            <div class="flex gap-4 flex-wrap">
                <a href="/packages/" class="nav-cta">Explore Packages</a>
                <a href="/request/" class="px-6 py-3 rounded-full border border-white/40 text-white hover:bg-white/10">Request a Custom Collab</a>
            </div>
            <div class="flex flex-wrap gap-4 text-sm text-emerald-100/80">
                <span>Trusted by growing SaaS and hardware brands</span>
                <span>•</span>
                <span>UGX + USD pricing</span>
                <span>•</span>
                <span>Secure Pesapal checkout</span>
            </div>
        </div>
        <div class="flex-1 w-full max-w-lg">
            <div class="bg-white/95 text-slate-900 rounded-3xl p-8 card-shadow">
                <div class="text-sm font-semibold text-emerald-600">Popular picks</div>
                <div class="mt-4 space-y-4">
                    <?php foreach (array_slice($packages, 0, 3) as $pkg): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-4 last:border-none last:pb-0">
                            <div>
                                <div class="font-semibold text-lg"><?= e($pkg['name']) ?></div>
                                <p class="text-sm text-slate-600 mt-1"><?= e($pkg['short_description']) ?></p>
                            </div>
                            <div class="text-emerald-600 font-semibold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="/packages/" class="inline-flex mt-5 text-sm font-semibold text-slate-800">See all packages →</a>
            </div>
        </div>
    </div>
</section>

<section class="container py-12">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Collaboration packages</h2>
            <p class="text-sm text-slate-500">Select one or more packages and submit your request.</p>
        </div>
        <a href="/packages/" class="text-sm font-semibold text-emerald-700">View all packages</a>
    </div>
    <form action="/request/" method="get" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($packages as $pkg): ?>
            <label class="block bg-white rounded-2xl p-6 border border-slate-200 card-shadow cursor-pointer transition hover:-translate-y-1">
                <div class="flex items-start gap-4">
                    <input type="checkbox" name="packages[]" value="<?= e($pkg['id']) ?>" class="mt-1 h-5 w-5 text-emerald-600 border-slate-300 rounded">
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900"><?= e($pkg['name']) ?></h3>
                            <span class="text-emerald-600 font-semibold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></span>
                        </div>
                        <p class="text-slate-600 text-sm"><?= e($pkg['short_description']) ?></p>
                        <p class="text-slate-500 text-xs">Delivery: <?= e($pkg['delivery_time_text']) ?></p>
                        <?php if ($pkg['allow_deposit']): ?>
                            <span class="badge">Deposit available (<?= e($pkg['deposit_percentage']) ?>%)</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(11,31,36,0.08); color: #0b1f24;">Full payment</span>
                        <?php endif; ?>
                    </div>
                </div>
            </label>
        <?php endforeach; ?>
        <div class="md:col-span-2 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <p class="text-sm text-slate-500">Choose the packages that fit your campaign. You can request multiple at once.</p>
            <button type="submit" class="nav-cta">Request Collaboration</button>
        </div>
    </form>
</section>

<section class="py-12" style="background: var(--surface-muted);">
    <div class="container">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Previous collaborations</h2>
                <p class="text-sm text-slate-500">A snapshot of recent brand integrations.</p>
            </div>
            <a href="/portfolio/" class="text-sm font-semibold text-emerald-700">View all</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($featured as $item): ?>
                <div class="bg-white rounded-2xl overflow-hidden card-shadow">
                    <div class="aspect-video">
                        <iframe class="w-full h-full" src="<?= e(str_replace('watch?v=', 'embed/', $item['youtube_url'])) ?>" allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div class="p-5 space-y-2">
                        <div class="text-xs uppercase tracking-wide text-emerald-600"><?= e($item['brand_name']) ?></div>
                        <div class="font-semibold text-slate-900"><?= e($item['title']) ?></div>
                        <p class="text-sm text-slate-600"><?= e($item['short_description']) ?></p>
                        <span class="text-xs text-slate-500"><?= e($item['collab_type']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($featured)): ?>
                <p class="text-slate-600">No featured collaborations yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
