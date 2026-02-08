<section class="relative overflow-hidden bg-[#0d0f12] text-white">
    <div class="absolute -left-24 top-8 h-72 w-72 rounded-full bg-[#ff0033]/20 blur-3xl"></div>
    <div class="absolute right-0 top-0 h-80 w-80 rounded-full bg-[#ffb347]/20 blur-3xl"></div>
    <div class="relative mx-auto w-[92vw] max-w-[1100px] lg:w-[68vw] py-16 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] items-center">
            <div class="space-y-6 animate-rise">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white/80">YouTube creator network</span>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-[3.6rem] leading-tight">High‑performing collaborations, built for brands that want trust.</h1>
                <p class="text-base text-white/75 max-w-xl">BenTech partners with ambitious teams to deliver premium YouTube integrations that feel natural, convert, and stay on brand.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="/request/" class="rounded-full bg-[#ff0033] px-6 py-3 text-sm font-semibold text-white shadow hover:bg-[#d9002c]">Start a request</a>
                    <a href="/packages/" class="rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-white hover:border-white/60">Explore packages</a>
                </div>
                <div class="flex flex-wrap gap-4 text-xs font-semibold text-white/60">
                    <span>UGX + USD pricing</span>
                    <span>Avg delivery 7–10 days</span>
                    <span>Secure Pesapal checkout</span>
                </div>
            </div>
            <div class="bg-white text-slate-900 rounded-3xl p-6 shadow-[0_25px_60px_rgba(15,23,42,0.18)] animate-rise delay-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Popular picks</p>
                        <h3 class="font-display text-lg text-slate-900">Launch‑ready options</h3>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-[#ff0033]/10 text-[#ff0033] flex items-center justify-center text-xl">▶</div>
                </div>
                <div class="mt-6 space-y-4">
                    <?php foreach (array_slice($packages, 0, 3) as $pkg): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4 last:border-none last:pb-0">
                            <div>
                                <h4 class="font-semibold text-slate-900"><?= e($pkg['name']) ?></h4>
                                <p class="text-sm text-slate-500"><?= e($pkg['short_description']) ?></p>
                            </div>
                            <div class="text-sm font-semibold text-[#ff0033]">
                                <?= format_money($pkg['base_price'], $pkg['currency']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="/packages/" class="mt-5 inline-flex text-sm font-semibold text-slate-800">See all packages →</a>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto w-[92vw] max-w-[1100px] lg:w-[68vw] py-12">
    <div class="grid gap-6 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm animate-rise">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">01</p>
            <h3 class="font-display mt-3 text-lg">Choose a format</h3>
            <p class="mt-2 text-sm text-slate-500">Pick a proven collaboration style or submit a custom brief.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm animate-rise delay-1">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">02</p>
            <h3 class="font-display mt-3 text-lg">Confirm deliverables</h3>
            <p class="mt-2 text-sm text-slate-500">We align on content, timing, and brand guidelines before production.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm animate-rise delay-2">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">03</p>
            <h3 class="font-display mt-3 text-lg">Go live</h3>
            <p class="mt-2 text-sm text-slate-500">Launch with confidence. Payments and status tracking stay transparent.</p>
        </div>
    </div>
</section>

<section class="bg-white/80 py-12">
    <div class="mx-auto w-[92vw] max-w-[1100px] lg:w-[68vw]">
        <div class="flex flex-col gap-2">
            <h2 class="font-display text-2xl text-slate-900">Current collaboration packages</h2>
            <p class="text-sm text-slate-500">A clean view of the options brands request most.</p>
        </div>
        <div class="mt-6 grid gap-5 md:grid-cols-2">
            <?php foreach (array_slice($packages, 0, 6) as $pkg): ?>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-display text-lg text-slate-900"><?= e($pkg['name']) ?></h3>
                            <p class="mt-2 text-sm text-slate-500"><?= e($pkg['short_description']) ?></p>
                            <p class="mt-3 text-xs text-slate-400">Delivery: <?= e($pkg['delivery_time_text']) ?></p>
                        </div>
                        <div class="text-sm font-semibold text-[#ff0033]"><?= format_money($pkg['base_price'], $pkg['currency']) ?></div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500"><?= $pkg['allow_deposit'] ? 'Deposit available' : 'Full payment' ?></span>
                        <a href="/request/?packages%5B%5D=<?= e($pkg['id']) ?>" class="text-sm font-semibold text-slate-900">Request →</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mx-auto w-[92vw] max-w-[1100px] lg:w-[68vw] py-14">
    <div class="flex flex-col gap-2">
        <h2 class="font-display text-2xl text-slate-900">Recent collaborations</h2>
        <p class="text-sm text-slate-500">A curated snapshot of brand partnerships.</p>
    </div>
    <div class="mt-6 grid gap-5 md:grid-cols-3">
        <?php foreach ($featured as $item): ?>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="aspect-video overflow-hidden rounded-xl">
                    <iframe class="h-full w-full" src="<?= e(str_replace('watch?v=', 'embed/', $item['youtube_url'])) ?>" allowfullscreen loading="lazy"></iframe>
                </div>
                <div class="mt-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400"><?= e($item['brand_name']) ?></p>
                    <h3 class="font-display mt-2 text-lg text-slate-900"><?= e($item['title']) ?></h3>
                    <p class="mt-2 text-sm text-slate-500"><?= e($item['short_description']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($featured)): ?>
            <p class="text-sm text-slate-500">No featured collaborations yet.</p>
        <?php endif; ?>
    </div>
</section>

<section class="mx-auto w-[92vw] max-w-[1100px] lg:w-[68vw] pb-16">
    <div class="rounded-3xl bg-[#0d0f12] px-8 py-10 text-white flex flex-col gap-4">
        <h2 class="font-display text-2xl">Ready to plan your collaboration?</h2>
        <p class="text-sm text-white/70">Send your brief and we will respond with timeline and next steps within 24 hours.</p>
        <div>
            <a href="/request/" class="inline-flex rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#0d0f12]">Start a request</a>
        </div>
    </div>
</section>
