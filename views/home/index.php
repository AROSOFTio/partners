<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy fade-up">
            <span class="badge">Creator partnerships</span>
            <h1 class="hero-title">Designed collaborations for brands that want measurable impact.</h1>
            <p class="hero-lead">Curated YouTube integrations, transparent pricing, and a fast onboarding path. We keep it simple so you can move quickly.</p>
            <div class="hero-actions">
                <a href="/packages/" class="cta-primary">Explore packages</a>
                <a href="/request/" class="cta-secondary">Request a custom collab</a>
            </div>
            <div class="hero-meta">
                <span>UGX + USD pricing</span>
                <span>•</span>
                <span>Avg delivery 7–10 days</span>
                <span>•</span>
                <span>Secure Pesapal checkout</span>
            </div>
        </div>
        <div class="hero-panel fade-up" style="animation-delay: .1s;">
            <div class="panel-title">Popular picks</div>
            <?php foreach (array_slice($packages, 0, 3) as $pkg): ?>
                <div class="panel-item">
                    <div>
                        <h4><?= e($pkg['name']) ?></h4>
                        <p><?= e($pkg['short_description']) ?></p>
                    </div>
                    <div class="panel-price"><?= format_money($pkg['base_price'], $pkg['currency']) ?></div>
                </div>
            <?php endforeach; ?>
            <a href="/packages/" class="panel-link">See all packages →</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div class="section-title">A clean, professional workflow</div>
            <div class="section-sub">Everything is structured so approvals and payments move fast.</div>
        </div>
        <div class="grid-3">
            <div class="card">
                <h3>1. Pick a package</h3>
                <p>Select a proven format or request a custom brief. We respond with timelines and next steps.</p>
            </div>
            <div class="card">
                <h3>2. Align on deliverables</h3>
                <p>We confirm the scope, content outline, and brand guidelines before production starts.</p>
            </div>
            <div class="card">
                <h3>3. Go live</h3>
                <p>Your collaboration launches with tracked payment status and post-release reporting.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <div class="section-title">Current collaboration packages</div>
            <div class="section-sub">A quick view of our most requested options.</div>
        </div>
        <div class="grid-3">
            <?php foreach (array_slice($packages, 0, 6) as $pkg): ?>
                <div class="card package-card">
                    <div class="panel-title">Package</div>
                    <h3><?= e($pkg['name']) ?></h3>
                    <p><?= e($pkg['short_description']) ?></p>
                    <div class="package-price"><?= format_money($pkg['base_price'], $pkg['currency']) ?></div>
                    <a href="/request/?packages%5B%5D=<?= e($pkg['id']) ?>" class="panel-link">Request this package</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div class="section-title">Recent collaborations</div>
            <div class="section-sub">Selected brand partnerships from our portfolio.</div>
        </div>
        <div class="grid-3">
            <?php foreach ($featured as $item): ?>
                <div class="card">
                    <div class="aspect-video" style="border-radius: 16px; overflow: hidden;">
                        <iframe class="w-full h-full" src="<?= e(str_replace('watch?v=', 'embed/', $item['youtube_url'])) ?>" allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div style="margin-top: 12px;">
                        <div class="panel-title"><?= e($item['brand_name']) ?></div>
                        <h3><?= e($item['title']) ?></h3>
                        <p><?= e($item['short_description']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($featured)): ?>
                <p class="section-sub">No featured collaborations yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="callout">
            <h2 class="section-title" style="color: #f7f7f2;">Ready to plan your collaboration?</h2>
            <p class="section-sub" style="color: rgba(247, 247, 242, 0.7);">Share your brief and we will send a clean proposal with pricing and timeline.</p>
            <a href="/request/" class="cta-secondary">Start a request →</a>
        </div>
    </div>
</section>

