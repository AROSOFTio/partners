<?php $isEditing = !empty($package); ?>
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex flex-col lg:flex-row gap-6">
        <aside class="w-full lg:w-64 bg-white border border-slate-200 rounded-xl card-shadow p-4 space-y-3">
            <div>
                <p class="badge mb-2">Admin</p>
                <h2 class="text-xl font-bold text-[#152228]">Navigation</h2>
            </div>
            <nav class="flex flex-col space-y-2 text-sm">
                <a href="/admin" class="px-3 py-2 rounded-lg hover:bg-slate-100">Dashboard</a>
                <a href="/admin/packages" class="px-3 py-2 rounded-lg bg-[#05C069]/10 text-[#152228] font-semibold">Packages</a>
                <a href="/admin/orders" class="px-3 py-2 rounded-lg hover:bg-slate-100">Orders</a>
                <a href="/admin/portfolio" class="px-3 py-2 rounded-lg hover:bg-slate-100">Portfolio</a>
                <a href="/admin/logout" class="px-3 py-2 rounded-lg hover:bg-red-50 text-red-600">Logout</a>
            </nav>
        </aside>

        <div class="flex-1 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="badge mb-2">Admin</p>
                    <h1 class="text-3xl font-bold text-[#152228]"><?= $isEditing ? 'Edit package' : 'Add package' ?></h1>
                </div>
                <a href="/admin/packages" class="px-4 py-2 bg-white border border-slate-200 rounded-lg">Back</a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-sm"><?= e($error) ?></div>
            <?php endif; ?>

            <form action="/admin/packages/edit/<?= $isEditing ? '?id=' . e($package['id']) : '' ?>" method="post" class="bg-white border border-slate-200 rounded-xl card-shadow p-6 space-y-4">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#152228]">Name</label>
                        <input type="text" name="name" class="mt-1 w-full border border-slate-300 rounded-lg p-3" value="<?= e($package['name'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#152228]">Slug</label>
                        <input type="text" name="slug" class="mt-1 w-full border border-slate-300 rounded-lg p-3" value="<?= e($package['slug'] ?? '') ?>" placeholder="dedicated-review-video">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-[#152228]">Short description</label>
                            <div class="flex gap-2 text-xs">
                                <button type="button" data-target="short_description" class="fmt-btn font-semibold text-slate-600">B</button>
                                <button type="button" data-target="short_description" data-tag="i" class="fmt-btn text-slate-600 italic">I</button>
                                <button type="button" data-target="short_description" data-tag="u" class="fmt-btn text-slate-600 underline">U</button>
                            </div>
                        </div>
                        <textarea id="short_description" name="short_description" rows="2" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required><?= e($package['short_description'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#152228]">Category</label>
                        <select name="category_id" class="mt-1 w-full border border-slate-300 rounded-lg p-3">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat['id']) ?>" <?= (!empty($package['category_id']) && $package['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-semibold text-[#152228]">Full description</label>
                        <div class="flex gap-2 text-xs">
                            <button type="button" data-target="full_description" class="fmt-btn font-semibold text-slate-600">B</button>
                            <button type="button" data-target="full_description" data-tag="i" class="fmt-btn text-slate-600 italic">I</button>
                            <button type="button" data-target="full_description" data-tag="u" class="fmt-btn text-slate-600 underline">U</button>
                            <button type="button" data-target="full_description" data-tag="ul" class="fmt-btn text-slate-600">• List</button>
                            <button type="button" data-target="full_description" data-tag="ol" class="fmt-btn text-slate-600">1. List</button>
                            <button type="button" data-target="full_description" data-tag="br" class="fmt-btn text-slate-600">Line</button>
                        </div>
                    </div>
                    <textarea id="full_description" name="full_description" rows="6" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required><?= e($package['full_description'] ?? '') ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#152228]">Price</label>
                        <input type="number" step="0.01" name="base_price" class="mt-1 w-full border border-slate-300 rounded-lg p-3" value="<?= e($package['base_price'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#152228]">Currency</label>
                        <input type="text" name="currency" class="mt-1 w-full border border-slate-300 rounded-lg p-3" value<?= '="' . e($package['currency'] ?? 'UGX') . '"' ?>>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#152228]">Duration (mins)</label>
                        <input type="number" name="duration_minutes" class="mt-1 w-full border border-slate-300 rounded-lg p-3" value="<?= e($package['duration_minutes'] ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#152228]">Delivery time text</label>
                        <input type="text" name="delivery_time_text" class="mt-1 w-full border border-slate-300 rounded-lg p-3" value="<?= e($package['delivery_time_text'] ?? '') ?>">
                    </div>
                    <div class="flex items-center gap-2 mt-6">
                        <input type="checkbox" name="allow_deposit" <?= !empty($package['allow_deposit']) ? 'checked' : '' ?>>
                        <span class="text-sm">Allow deposit</span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#152228]">Deposit percentage</label>
                        <input type="number" step="0.01" name="deposit_percentage" class="mt-1 w-full border border-slate-300 rounded-lg p-3" value="<?= e($package['deposit_percentage'] ?? '50.00') ?>">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" <?= (!isset($package['is_active']) || $package['is_active']) ? 'checked' : '' ?>>
                    <span class="text-sm">Active</span>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Save package</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    (function() {
        function wrapSelection(textarea, before, after) {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const val = textarea.value;
            const selected = val.slice(start, end);
            const next = val.slice(0, start) + before + selected + after + val.slice(end);
            textarea.value = next;
            const cursor = start + before.length + selected.length + after.length;
            textarea.focus();
            textarea.setSelectionRange(cursor, cursor);
        }
        function applyList(textarea, type) {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const val = textarea.value;
            const selected = val.slice(start, end) || 'Item';
            const items = selected.split(/\r?\n/).filter(Boolean);
            const li = items.map(i => '<li>' + i + '</li>').join('');
            const block = '<' + type + '>' + li + '</' + type + '>';
            const next = val.slice(0, start) + block + val.slice(end);
            textarea.value = next;
            const cursor = start + block.length;
            textarea.focus();
            textarea.setSelectionRange(cursor, cursor);
        }
        document.querySelectorAll('.fmt-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-target');
                const tag = btn.getAttribute('data-tag') || 'b';
                const textarea = document.getElementById(targetId);
                if (!textarea) return;
                if (tag === 'ul' || tag === 'ol') {
                    applyList(textarea, tag);
                    return;
                }
                if (tag === 'br') {
                    wrapSelection(textarea, '<br>', '');
                    return;
                }
                wrapSelection(textarea, '<' + tag + '>', '</' + tag + '>');
            });
        });
    })();
</script>
