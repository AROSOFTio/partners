<div class="max-w-5xl mx-auto px-4 py-10 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="badge mb-2">Admin</p>
            <h1 class="text-3xl font-bold text-[#152228]">Order <?= e($order['order_code']) ?></h1>
        </div>
        <a href="/admin/orders" class="px-4 py-2 bg-white border border-slate-200 rounded-lg">Back to orders</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4 space-y-2">
            <div class="text-sm text-slate-500">Customer</div>
            <div class="font-semibold text-[#152228]"><?= e($order['customer_name']) ?></div>
            <div class="text-sm text-slate-600"><?= e($order['customer_email']) ?></div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4 space-y-2">
            <div class="text-sm text-slate-500">Payment type</div>
            <div class="font-semibold text-[#152228]"><?= e($order['payment_type']) ?></div>
            <div class="text-sm text-slate-600">Due now: <?= format_money($order['amount_due_now'], $order['currency']) ?></div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4 space-y-2">
            <div class="text-sm text-slate-500">Status</div>
            <div class="font-semibold text-[#152228]"><?= e($order['status']) ?></div>
            <div class="text-sm text-slate-600">Total: <?= format_money($order['total_amount'], $order['currency']) ?></div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4">
        <h2 class="font-semibold text-[#152228] mb-2">Packages</h2>
        <ul class="divide-y divide-slate-200">
            <?php foreach ($items as $item): ?>
                <li class="py-2 flex justify-between text-sm">
                    <span><?= e($item['package_name_snapshot']) ?></span>
                    <span><?= format_money($item['unit_price'], $order['currency']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4">
        <h2 class="font-semibold text-[#152228] mb-2">Payments</h2>
        <?php if ($payments): ?>
            <table class="w-full text-sm">
                <thead>
                <tr class="text-left text-slate-600">
                    <th class="py-2">Amount</th>
                    <th class="py-2">Type</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Tracking</th>
                    <th class="py-2">Created</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $pay): ?>
                    <tr class="border-t border-slate-200">
                        <td class="py-2"><?= format_money($pay['amount'], $pay['currency']) ?></td>
                        <td class="py-2"><?= e($pay['payment_type']) ?></td>
                        <td class="py-2 text-xs uppercase font-semibold"><?= e($pay['status']) ?></td>
                        <td class="py-2 text-xs"><?= e($pay['pesapal_transaction_tracking_id']) ?></td>
                        <td class="py-2 text-slate-600"><?= e($pay['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-sm text-slate-600">No payments yet.</p>
        <?php endif; ?>
    </div>
</div>
