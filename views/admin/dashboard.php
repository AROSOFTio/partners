<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex flex-col lg:flex-row gap-6">
        <aside class="w-full lg:w-64 bg-white border border-slate-200 rounded-xl card-shadow p-4 space-y-3">
            <div>
                <p class="badge mb-2">Admin</p>
                <h2 class="text-xl font-bold text-[#152228]">Control Panel</h2>
            </div>
            <nav class="flex flex-col space-y-2 text-sm">
                <a href="/admin" class="px-3 py-2 rounded-lg bg-[#05C069]/10 text-[#152228] font-semibold">Dashboard</a>
                <a href="/admin/packages" class="px-3 py-2 rounded-lg hover:bg-slate-100">Packages</a>
                <a href="/admin/orders" class="px-3 py-2 rounded-lg hover:bg-slate-100">Orders</a>
                <a href="/admin/portfolio" class="px-3 py-2 rounded-lg hover:bg-slate-100">Portfolio</a>
                <a href="/admin/logout" class="px-3 py-2 rounded-lg hover:bg-red-50 text-red-600">Logout</a>
            </nav>
        </aside>

        <div class="flex-1 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="badge mb-2">Admin</p>
                    <h1 class="text-3xl font-bold text-[#152228]">Dashboard</h1>
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
    </div>
</div>
