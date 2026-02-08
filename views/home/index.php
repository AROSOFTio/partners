<section class="hero">
    <div class="container hero-inner">
        <div class="hero-copy fade-up">
            <span class="hero-badge">YouTube creator network</span>
            <h1 class="hero-title">High‑performing collaborations, styled for brands that want trust.</h1>
            <p class="hero-lead">BenTech partners with ambitious teams to deliver premium YouTube integrations that feel natural, convert, and stay on brand.</p>
            <div class="hero-actions">
                <a href="/request/" class="btn-primary">Start a request</a>
                <a href="/packages/" class="btn-secondary">Explore packages</a>
            </div>
            <div class="hero-meta">
                <span>UGX + USD pricing</span>
                <span>Avg delivery 7–10 days</span>
                <span>Secure Pesapal checkout</span>
            </div>
        </div>
        <div class="hero-panel fade-up delay-1">
            <div class="panel-header">
                <div>
                    <div class="panel-label">Popular picks</div>
                    <div class="font-semibold">Launch-ready options</div>
                </div>
                <div class="badge">Live now</div>
            </div>
            <?php foreach (array_slice($packages, 0, 3) as $pkg): ?>
                <div class="panel-item">
                    <div>
                        <h4><?= e($pkg['name']) ?></h4>
                        <p><?= e($pkg['short_description']) ?></p>
                    </div>
                    <div class="panel-price"><?= format_money($pkg['base_price'], $pkg['currency']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div>
            <div class="section-title">A simple, professional flow</div>
            <div class="section-sub">Everything is structured so approvals and payments move fast.</div>
        </div>
        <div class="grid-3" style="margin-top: 24px;">
            <div class="card">
                <h3>Pick a format</h3>
                <p>Select a proven package or share a custom brief. We respond with timelines and next steps.</p>
            </div>
            <div class="card">
                <h3>Confirm deliverables</h3>
                <p>We align on creative direction, integrations, and brand guidelines before production begins.</p>
            </div>
            <div class="card">
                <h3>Launch with confidence</h3>
                <p>Your collaboration goes live with tracked payment status and post-release reporting.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div>
            <div class="section-title">Current collaboration packages</div>
            <div class="section-sub">A curated selection of the most requested options.</div>
        </div>
        <div class="package-grid">
            <?php foreach (array_slice($packages, 0, 6) as $pkg): ?>
                <div class="card package-card">
                    <div class="badge">Package</div>
                    <h3><?= e($pkg['name']) ?></h3>
                    <p><?= e($pkg['short_description']) ?></p>
                    <div class="package-price"><?= format_money($pkg['base_price'], $pkg['currency']) ?></div>
                    <a href="/request/?packages%5B%5D=<?= e($pkg['id']) ?>" class="panel-label" style="text-decoration: none;">Request this package →</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div>
            <div class="section-title">Recent collaborations</div>
            <div class="section-sub">Selected brand partnerships from our portfolio.</div>
        </div>
        <div class="grid-3" style="margin-top: 24px;">
            <?php foreach ($featured as $item): ?>
                <div class="card">
                    <div class="aspect-video" style="border-radius: 14px; overflow: hidden;">
                        <iframe class="w-full h-full" src="<?= e(str_replace('watch?v=', 'embed/', $item['youtube_url'])) ?>" allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div style="margin-top: 12px;">
                        <div class="panel-label"><?= e($item['brand_name']) ?></div>
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
            <div class="section-title" style="color: #f7f7f2;">Ready to plan your collaboration?</div>
            <div class="section-sub" style="color: rgba(247, 247, 242, 0.7);">Share your brief and we will reply with a clean proposal within 24 hours.</div>
            <div>
                <a href="/request/" class="btn-secondary" style="border-color: rgba(247,247,242,0.4);">Start a request →</a>
            </div>
        </div>
    </div>
</section>
