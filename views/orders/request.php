<?php
$total = 0;
$depositTotal = 0;
$depositAllowed = !empty($selectedPackages);
foreach ($selectedPackages as $pkg) {
    $total += (float)$pkg['base_price'];
    if (!empty($pkg['allow_deposit'])) {
        $depositTotal += (float)$pkg['base_price'] * ((float)$pkg['deposit_percentage'] / 100);
    } else {
        $depositAllowed = false;
    }
}
?>
<form action="/request/" method="post" class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <?= csrf_field() ?>
    <div class="lg:col-span-2 space-y-6">
        <div>
            <p class="badge mb-3">Request collaboration</p>
            <h1 class="text-3xl font-bold text-[#152228]">Tell us about your campaign</h1>
            <p class="text-slate-600">Select packages on the right, add a custom request if needed, then share your brief.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                <ul class="list-disc ml-5 space-y-1">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Your Name *</label>
                <input type="text" name="customer_name" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Email *</label>
                <input type="email" name="customer_email" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Brand / Product name *</label>
                <input type="text" name="company_name" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Website or product URL</label>
                <input type="text" name="website_url" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="https://">
            </div>
        </div>
        <div>
            <div class="flex items-center justify-between">
                <label class="block text-sm font-semibold text-[#152228]">Short brief / campaign details *</label>
                <div class="flex gap-2 text-xs">
                    <button type="button" data-target="brief" class="fmt-btn font-semibold text-slate-600">B</button>
                    <button type="button" data-target="brief" data-tag="i" class="fmt-btn text-slate-600 italic">I</button>
                    <button type="button" data-target="brief" data-tag="u" class="fmt-btn text-slate-600 underline">U</button>
                    <button type="button" data-target="brief" data-tag="ul" class="fmt-btn text-slate-600">• List</button>
                    <button type="button" data-target="brief" data-tag="ol" class="fmt-btn text-slate-600">1. List</button>
                    <button type="button" data-target="brief" data-tag="br" class="fmt-btn text-slate-600">Line</button>
                </div>
            </div>
            <textarea id="brief" name="brief" rows="4" class="mt-1 w-full border border-slate-300 rounded-lg p-3 fmt-area" required></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Preferred timeline</label>
                <input type="text" name="preferred_timeline" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="e.g. 5-7 business days">
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#152228] mb-2">Payment option</label>
                <div class="space-y-2 bg-white border border-slate-200 rounded-lg p-3">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" name="payment_type" value="full" checked> <span>Full payment (100%)</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm <?= $depositAllowed ? '' : 'opacity-50 cursor-not-allowed' ?>">
                        <input type="radio" name="payment_type" value="deposit" <?= $depositAllowed ? '' : 'disabled' ?>>
                        <span>50% deposit (if eligible)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl card-shadow p-4 space-y-4">
            <h2 class="font-semibold text-[#152228]">Add a custom package (optional)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">Title</label>
                    <input type="text" name="custom_name" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="Custom collab (e.g. Live stream feature)">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">Price (<?= e(get_display_currency()) ?>)</label>
                    <input type="number" step="0.01" name="custom_price" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="e.g. 150.00">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">Delivery time</label>
                    <input type="text" name="custom_delivery_time" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="e.g. 5-7 business days">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#152228]">Duration (mins)</label>
                    <input type="number" name="custom_duration" class="mt-1 w-full border border-slate-300 rounded-lg p-3" placeholder="e.g. 6">
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-semibold text-[#152228]">Description</label>
                    <div class="flex gap-2 text-xs">
                        <button type="button" data-target="custom_description" class="fmt-btn font-semibold text-slate-600">B</button>
                        <button type="button" data-target="custom_description" data-tag="i" class="fmt-btn text-slate-600 italic">I</button>
                        <button type="button" data-target="custom_description" data-tag="u" class="fmt-btn text-slate-600 underline">U</button>
                        <button type="button" data-target="custom_description" data-tag="ul" class="fmt-btn text-slate-600">• List</button>
                        <button type="button" data-target="custom_description" data-tag="ol" class="fmt-btn text-slate-600">1. List</button>
                        <button type="button" data-target="custom_description" data-tag="br" class="fmt-btn text-slate-600">Line</button>
                    </div>
                </div>
                <textarea id="custom_description" name="custom_description" rows="3" class="mt-1 w-full border border-slate-300 rounded-lg p-3 fmt-area" placeholder="Describe the custom collaboration"></textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-full hover:opacity-90">Continue to summary</button>
        </div>
    </div>

    <aside class="bg-white rounded-xl border border-slate-200 card-shadow p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-[#152228]">Select packages</h2>
            <a href="/packages" class="text-sm text-[#05C069] font-semibold">View details</a>
        </div>
        <div class="space-y-3 max-h-[420px] overflow-y-auto pr-2">
            <?php foreach ($allPackages as $pkg): ?>
                <label class="flex items-start gap-3 text-sm border border-slate-200 rounded-lg p-3">
                    <input type="checkbox" name="packages[]" value="<?= e($pkg['id']) ?>" class="mt-1 h-4 w-4 text-[#05C069]" <?= in_array($pkg['id'], $selectedIds) ? 'checked' : '' ?>>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-[#152228]"><?= e($pkg['name']) ?></span>
                            <span class="text-[#05C069] font-semibold"><?= format_money($pkg['base_price'], $pkg['currency']) ?></span>
                        </div>
                        <p class="text-slate-600 text-xs mt-1"><?= safe_html($pkg['short_description']) ?></p>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
        <div class="border-t border-slate-200 pt-3 text-sm space-y-1">
            <div class="flex justify-between font-semibold">
                <span>Total (100%)</span>
                <span><?= $selectedPackages ? format_money($total, $selectedPackages[0]['currency']) : 'UGX 0.00' ?></span>
            </div>
            <?php if ($depositAllowed && $selectedPackages): ?>
                <div class="flex justify-between text-[#05C069] font-semibold">
                    <span>Deposit (50% blended)</span>
                    <span><?= format_money($depositTotal, $selectedPackages[0]['currency']) ?></span>
                </div>
            <?php endif; ?>
        </div>
        <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-xs text-slate-600">
            Payments are securely processed by AROSOFT Innovations Ltd via Pesapal. You will review the summary before paying.
        </div>
    </aside>
</form>
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
        document.querySelectorAll('.fmt-area').forEach(area => {
            area.addEventListener('keydown', (e) => {
                if (!e.ctrlKey) return;
                const key = e.key.toLowerCase();
                if (key === 'b' || key === 'i' || key === 'u') {
                    e.preventDefault();
                    wrapSelection(area, '<' + key + '>', '</' + key + '>');
                }
            });
        });
    })();
</script>
