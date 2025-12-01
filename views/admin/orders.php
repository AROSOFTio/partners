<div class="max-w-6xl mx-auto px-4 py-10 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="badge mb-2">Admin</p>
            <h1 class="text-3xl font-bold text-[#152228]">Orders</h1>
        </div>
        <a href="/admin" class="px-4 py-2 bg-white border border-slate-200 rounded-lg">Dashboard</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
            <tr class="text-left text-slate-600">
                <th class="py-2">Code</th>
                <th class="py-2">Customer</th>
                <th class="py-2">Total</th>
                <th class="py-2">Status</th>
                <th class="py-2">Created</th>
                <th class="py-2"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr class="border-t border-slate-200">
                    <td class="py-2 font-semibold text-[#152228]"><?= e($order['order_code']) ?></td>
                    <td class="py-2"><?= e($order['customer_name']) ?></td>
                    <td class="py-2"><?= format_money($order['total_amount'], $order['currency']) ?></td>
                    <td class="py-2 text-xs uppercase font-semibold"><?= e($order['status']) ?></td>
                    <td class="py-2 text-slate-600"><?= e($order['created_at']) ?></td>
                    <td class="py-2 text-right"><a href="/admin/orders/view?id=<?= e($order['id']) ?>" class="text-[#05C069] font-semibold">View</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
