<div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <p class="badge mb-2">Admin</p>
            <h1 class="text-3xl font-bold text-[#152228]">Dashboard</h1>
        </div>
        <div class="flex gap-3 text-sm">
            <a href="/admin/packages" class="px-4 py-2 bg-white border border-slate-200 rounded-lg">Packages</a>
            <a href="/admin/orders" class="px-4 py-2 bg-white border border-slate-200 rounded-lg">Orders</a>
            <a href="/admin/portfolio" class="px-4 py-2 bg-white border border-slate-200 rounded-lg">Portfolio</a>
            <a href="/admin/logout" class="px-4 py-2 bg-red-50 border border-red-200 text-red-600 rounded-lg">Logout</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4 card-shadow">
            <div class="text-sm text-slate-500">Total orders</div>
            <div class="text-2xl font-bold text-[#152228]"><?= e($stats['orders']) ?></div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 card-shadow">
            <div class="text-sm text-slate-500">Paid</div>
            <div class="text-2xl font-bold text-[#05C069]"><?= e($stats['paid']) ?></div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 card-shadow">
            <div class="text-sm text-slate-500">Pending</div>
            <div class="text-2xl font-bold text-[#152228]"><?= e($stats['pending']) ?></div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 card-shadow">
            <div class="text-sm text-slate-500">Active packages</div>
            <div class="text-2xl font-bold text-[#152228]"><?= e($stats['packages']) ?></div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-[#152228]">Recent orders</h2>
            <a href="/admin/orders" class="text-sm text-[#05C069] font-semibold">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                <tr class="text-left text-slate-600">
                    <th class="py-2">Code</th>
                    <th class="py-2">Customer</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Total</th>
                    <th class="py-2">Date</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($recentOrders as $order): ?>
                    <tr class="border-t border-slate-200">
                        <td class="py-2 font-semibold"><a href="/admin/orders/view?id=<?= e($order['id']) ?>" class="text-[#152228]"><?= e($order['order_code']) ?></a></td>
                        <td class="py-2"><?= e($order['customer_name']) ?></td>
                        <td class="py-2 uppercase text-xs font-semibold"><?= e($order['status']) ?></td>
                        <td class="py-2"><?= format_money($order['total_amount'], $order['currency']) ?></td>
                        <td class="py-2 text-slate-600"><?= e($order['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
