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
                <a href="/admin/orders" class="px-3 py-2 rounded-lg hover:bg-slate-100">Orders</a>
                <a href="/admin/portfolio" class="px-3 py-2 rounded-lg bg-[#05C069]/10 text-[#152228] font-semibold">Portfolio</a>
                <a href="/admin/logout" class="px-3 py-2 rounded-lg hover:bg-red-50 text-red-600">Logout</a>
            </nav>
        </aside>

        <div class="flex-1 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="badge mb-2">Admin</p>
                    <h1 class="text-3xl font-bold text-[#152228]">Portfolio</h1>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-sm"><?= e($error) ?></div>
            <?php endif; ?>

            <form action="/admin/portfolio/" method="post" class="bg-white border border-slate-200 rounded-xl card-shadow p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <?= csrf_field() ?>
                <div class="md:col-span-2">
                    <h2 class="font-semibold text-[#152228] mb-2">Add / Update item</h2>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">ID (for update)</label>
                    <input type="number" name="id" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="Leave blank to add new">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">Title</label>
                    <input type="text" name="title" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">Brand name</label>
                    <input type="text" name="brand_name" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">YouTube URL</label>
                    <input type="text" name="youtube_url" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="https://www.youtube.com/watch?v=..." required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">Collab type</label>
                    <input type="text" name="collab_type" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="Dedicated review" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-[#152228]">Short description</label>
                    <textarea name="short_description" rows="3" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1">
                    <span class="text-sm">Featured on home page</span>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Save item</button>
                </div>
            </form>

            <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="text-left text-slate-600">
                        <th class="py-2">ID</th>
                        <th class="py-2">Title</th>
                        <th class="py-2">Brand</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Featured</th>
                        <th class="py-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr class="border-t border-slate-200">
                            <td class="py-2"><?= e($item['id']) ?></td>
                            <td class="py-2 font-semibold text-[#152228]"><?= e($item['title']) ?></td>
                            <td class="py-2"><?= e($item['brand_name']) ?></td>
                            <td class="py-2"><?= e($item['collab_type']) ?></td>
                            <td class="py-2"><?= $item['is_featured'] ? 'Yes' : 'No' ?></td>
                            <td class="py-2 text-right">
                                <form action="/admin/portfolio/" method="post" class="inline-block">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="delete_id" value="<?= e($item['id']) ?>">
                                    <button type="submit" class="text-red-600" onclick="return confirm('Delete this item?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
