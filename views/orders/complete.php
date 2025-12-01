<div class="max-w-3xl mx-auto px-4 py-12 text-center space-y-6">
    <?php $success = ($status === 'successful' || $status === 'deposit_paid' || $status === 'paid_full'); ?>
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full <?= $success ? 'bg-emerald-100' : 'bg-red-100' ?>">
        <?php if ($success): ?>
            <span class="text-[#05C069] text-3xl">?</span>
        <?php else: ?>
            <span class="text-red-500 text-3xl">!</span>
        <?php endif; ?>
    </div>
    <h1 class="text-3xl font-bold text-[#152228]">Payment <?= $success ? 'confirmed' : 'status' ?></h1>
    <p class="text-slate-600">Order <strong><?= e($order['order_code']) ?></strong></p>
    <p class="text-slate-700">Thank you for collaborating with BenTech. We will review your brief and confirm timelines.</p>
    <div class="bg-white border border-slate-200 rounded-xl card-shadow p-6 text-left">
        <div class="flex justify-between text-sm">
            <span>Status</span>
            <span class="font-semibold"><?= e($order['status']) ?></span>
        </div>
        <div class="flex justify-between text-sm">
            <span>Amount paid</span>
            <span class="font-semibold text-[#05C069]"><?= format_money($order['amount_due_now'], $order['currency']) ?></span>
        </div>
        <div class="mt-3">
            <h2 class="font-semibold text-[#152228] mb-2">Packages</h2>
            <ul class="list-disc ml-5 text-sm text-slate-700 space-y-1">
                <?php foreach ($items as $item): ?>
                    <li><?= e($item['package_name_snapshot']) ?> — <?= format_money($item['unit_price'], $order['currency']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="flex gap-3 justify-center">
        <a href="/packages" class="px-5 py-3 rounded-full border border-slate-300 text-[#152228] font-semibold">Explore more packages</a>
        <a href="/" class="px-5 py-3 rounded-full bg-[#05C069] text-[#152228] font-semibold">Back to home</a>
    </div>
</div>
