<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex flex-col lg:flex-row gap-6">
        <aside class="w-full lg:w-64 bg-white border border-slate-200 rounded-xl card-shadow p-4 space-y-3">
            <div>
                <p class="badge mb-2">Admin</p>
                <h2 class="text-xl font-bold text-[#152228]">Navigation</h2>
            </div>
            <nav class="flex flex-col space-y-2 text-sm">
                <a href="/admin" class="px-3 py-2 rounded-lg hover:bg-slate-100">Dashboard</a>
                <a href="/admin/packages" class="px-3 py-2 rounded-lg hover:bg-slate-100">Packages</a>
                <a href="/admin/orders" class="px-3 py-2 rounded-lg bg-[#05C069]/10 text-[#152228] font-semibold">Orders</a>
                <a href="/admin/portfolio" class="px-3 py-2 rounded-lg hover:bg-slate-100">Portfolio</a>
                <a href="/admin/logout" class="px-3 py-2 rounded-lg hover:bg-red-50 text-red-600">Logout</a>
            </nav>
        </aside>

        <div class="flex-1 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="badge mb-2">Admin</p>
                    <h1 class="text-3xl font-bold text-[#152228]">Orders</h1>
                </div>
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
    </div>
</div>
