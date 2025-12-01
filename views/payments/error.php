<div class="max-w-3xl mx-auto px-4 py-16 text-center space-y-4">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-500 text-3xl">!</div>
    <h1 class="text-3xl font-bold text-[#152228]">Payment Error</h1>
    <p class="text-slate-600"><?= e($message ?? 'Something went wrong while processing your payment. Please try again or contact support.') ?></p>
    <a href="/checkout" class="px-5 py-3 rounded-full bg-[#05C069] text-[#152228] font-semibold">Back to checkout</a>
</div>
