<!-- Refund Information -->
<x-admin::accordion>
    <x-slot:header>
        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
            @lang('admin::app.sales.orders.view.refund')
        </p>
    </x-slot>

    <x-slot:content>
        @forelse ($order->refunds as $refund)
            <div class="grid gap-y-2.5">
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.sales.orders.view.refund-id', ['refund' => $refund->id])
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        {{ core()->formatDate($refund->created_at, 'd M, Y H:i:s a') }}
                    </p>

                    <p class="mt-4 font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.sales.orders.view.name')
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $refund->order->customer_full_name }}
                    </p>

                    <p class="mt-4 font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.sales.orders.view.status')
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        @lang('admin::app.sales.orders.view.refunded')

                        <span class="font-semibold text-gray-800 dark:text-white">
                                            {{ core()->formatBasePrice($refund->base_grand_total) }}
                                        </span>
                    </p>
                </div>

                <div class="flex gap-2.5">
                    <a
                        href="{{ route('admin.sales.refunds.view', $refund->id) }}"
                        class="text-sm text-blue-600 transition-all hover:underline"
                    >
                        @lang('admin::app.sales.orders.view.view')
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-300">
                @lang('admin::app.sales.orders.view.no-refund-found')
            </p>
        @endforelse
    </x-slot>
</x-admin::accordion>
