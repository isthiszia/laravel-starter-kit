<x-layouts::app :title="__('Dashboard')">
    @if ($showAlert && $payment)
        <div class="mb-4 rounded-xl border border-amber-300 bg-amber-50 p-4 dark:border-amber-700 dark:bg-amber-950/40">
            <div class="flex items-start gap-3">

                <div class="flex-1">
                    <h3 class="font-semibold text-amber-800 dark:text-amber-200">
                        Subscription Expiring Soon
                    </h3>

                    <p class="mt-1 text-sm text-amber-700 dark:text-amber-300">
                        Your subscription is expiring soon.

                        @if ($daysLeft == 0)
                            It expires today.
                        @elseif ($daysLeft == 1)
                            Only 1 day is remaining.
                        @else
                            Only {{ $daysLeft }} days are remaining.
                        @endif
                    </p>

                    <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                        Due date:
                        {{ \Carbon\Carbon::parse($payment->due_date)->format('d M Y') }}
                    </p>
                </div>

            </div>
        </div>
    @endif
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts::app>
