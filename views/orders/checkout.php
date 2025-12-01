<div class="max-w-4xl mx-auto px-4 py-12">
    <p class="badge mb-3">Review & Pay</p>
    <h1 class="text-3xl font-bold text-[#152228] mb-4">Order <?= e($order['order_code']) ?></h1>
    <div class="bg-white rounded-xl border border-slate-200 card-shadow p-6 space-y-4">
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-slate-600">Customer</span>
                <span class="font-semibold text-[#152228]"><?= e($order['customer_name']) ?> (<?= e($order['customer_email']) ?>)</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-600">Payment option</span>
                <span class="font-semibold text-[#152228]"><?= $order['payment_type'] === 'deposit' ? '50% deposit now' : 'Full payment' ?></span>
            </div>
        </div>

        <div>
            <h2 class="font-semibold text-[#152228] mb-2">Packages</h2>
            <ul class="divide-y divide-slate-200">
                <?php foreach ($items as $item): ?>
                    <li class="py-2 flex justify-between text-sm">
                        <span><?= e($item['package_name_snapshot']) ?></span>
                        <span class="font-semibold text-[#05C069]"><?= format_money($item['unit_price'], $order['currency']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="border-t border-slate-200 pt-3 text-sm space-y-1">
            <div class="flex justify-between">
                <span>Total</span>
                <span class="font-semibold text-[#152228]"><?= format_money($order['total_amount'], $order['currency']) ?></span>
            </div>
            <div class="flex justify-between text-lg">
                <span class="font-semibold">Due now</span>
                <span class="font-bold text-[#05C069]"><?= format_money($order['amount_due_now'], $order['currency']) ?></span>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-xs text-slate-600">
            Payments are securely processed by AROSOFT Innovations Ltd via Pesapal.
        </div>

        <form action="/payment/create" method="post" class="flex justify-end">
            <input type="hidden" name="order_code" value="<?= e($order['order_code']) ?>">
            <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Pay with Pesapal</button>
        </form>
    </div>
</div>
