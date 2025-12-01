<div class="max-w-6xl mx-auto px-4 py-12">
    <p class="badge mb-3">Portfolio</p>
    <h1 class="text-3xl font-bold text-[#152228] mb-6">Previous collaborations</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($items as $item): ?>
            <div class="bg-white rounded-xl border border-slate-200 card-shadow overflow-hidden">
                <div class="aspect-video">
                    <iframe class="w-full h-full" src="<?= e(str_replace('watch?v=', 'embed/', $item['youtube_url'])) ?>" allowfullscreen loading="lazy"></iframe>
                </div>
                <div class="p-4 space-y-2">
                    <div class="text-sm text-[#05C069]"><?= e($item['brand_name']) ?></div>
                    <div class="font-semibold text-[#152228]"><?= e($item['title']) ?></div>
                    <p class="text-sm text-slate-600"><?= e($item['short_description']) ?></p>
                    <span class="text-xs uppercase tracking-wide text-slate-500"><?= e($item['collab_type']) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
            <p class="text-slate-600">No collaborations available yet.</p>
        <?php endif; ?>
    </div>
</div>
