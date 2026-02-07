<div class="min-h-screen flex items-center justify-center bg-slate-100 px-4">
    <div class="bg-white w-full max-w-md rounded-xl border border-slate-200 card-shadow p-8 space-y-4">
        <div class="text-center">
            <div class="text-2xl font-bold text-[#152228]">Admin Login</div>
            <div class="text-sm text-slate-600">BenTech Collaborations</div>
        </div>
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-sm"><?= e($error) ?></div>
        <?php endif; ?>
        <form action="/admin/login/" method="post" class="space-y-3">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Email</label>
                <input type="email" name="email" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#152228]">Password</label>
                <input type="password" name="password" class="mt-1 w-full border border-slate-300 rounded-lg p-3" required>
            </div>
            <button type="submit" class="w-full px-4 py-3 bg-[#05C069] text-[#152228] font-semibold rounded-lg hover:opacity-90">Sign in</button>
        </form>
    </div>
</div>
