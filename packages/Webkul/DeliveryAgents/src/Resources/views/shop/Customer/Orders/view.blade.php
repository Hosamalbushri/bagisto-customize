@if ($order->showDeliveryTab())
    <x-shop::tabs.item
    class="max-md:!px-0 max-md:py-1.5"
    :title="trans('deliveryAgent::app.shop.customer.account.orders.view.delivered.delivery')"
>
    <!-- Mobile Layout -->
    <div class="grid gap-4 md:hidden">
        <!-- بيانات المندوب -->
        <x-shop::accordion :is-active="true" class="overflow-hidden rounded-lg !border-none !bg-gray-100">
            <x-slot:header class="!mb-0 rounded-t-md bg-gray-100 !px-4 py-2.5 text-sm font-medium max-sm:py-2">
                بيانات المندوب
            </x-slot>

            <x-slot:content class="!bg-gray-100 !p-0">
                <div class="rounded-md rounded-t-none border border-t-0 bg-white px-4 py-3 text-sm">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-zinc-500">الاسم</span>
                        <span class="text-black font-medium">—</span> <!-- place: recipient name -->
                    </div>

                    <div class="flex justify-between items-center py-2 border-t border-gray-100">
                        <span class="text-zinc-500">الهاتف</span>
                        <span class="text-black font-medium">—</span> <!-- place: phone -->
                    </div>
                </div>

                <!-- زر التقييم -->
                    @include('DeliveryAgents::shop.reviews.create')
            </x-slot>
        </x-shop::accordion>

        <!-- تفاصيل التوصيل -->
        <x-shop::accordion :is-active="true" class="overflow-hidden rounded-lg !border-none !bg-gray-100">
            <x-slot:header class="!mb-0 rounded-t-md bg-gray-100 !px-4 py-3 text-sm font-medium max-sm:py-2">
                تفاصيل التوصيل
            </x-slot>

            <x-slot:content class="grid gap-2.5 !bg-gray-100 !p-0">
                <div class="rounded-md rounded-t-none border border-t-0 bg-white px-4 py-3 text-xs font-medium">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">طريقة الشحن</span>
                        <span class="text-black">—</span> <!-- place: shipping method -->
                    </div>

                    <div class="flex justify-between">
                        <span class="text-zinc-500">رقم التتبع</span>
                        <span class="text-black">—</span> <!-- place: tracking number -->
                    </div>

                    <div class="flex justify-between">
                        <span class="text-zinc-500">حالة التوصيل</span>
                        <span class="text-black">—</span> <!-- place: delivery status -->
                    </div>

                    <div class="flex justify-between">
                        <span class="text-zinc-500">موعد التسليم المتوقع</span>
                        <span class="text-black">—</span> <!-- place: estimated delivery -->
                    </div>
                </div>
            </x-slot>
        </x-shop::accordion>
    </div>

    <!-- Desktop Layout -->
    <div class="max-md:hidden">
        <div class="grid grid-cols-2 gap-6 max-lg:grid-cols-1">
            <div class="rounded-xl border">
                <div class="border-b bg-zinc-100 px-5 py-3 text-sm font-semibold text-black">
                    بيانات المندوب
                </div>
                @forelse (\Illuminate\Support\Arr::wrap($order->deliveryAgent) as $delivery_agent)
                    <div class="px-5 py-4 text-sm">
                        <div class="flex w-full justify-between items-center py-3">
                            <span class="text-zinc-500">الاسم</span>
                            <span class="text-black font-medium">{{$delivery_agent->name}}</span>
                        </div>

                        <div class="flex w-full justify-between items-center py-3 border-t border-gray-100">
                            <span class="text-zinc-500">الهاتف</span>
                            <span class="text-black font-medium">{{$delivery_agent->phone}}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-4 text-sm">
                        <div class="flex w-full justify-between items-center py-3">
                            <span class="text-zinc-500">الاسم</span>
                            <span class="text-black font-medium">—</span>
                        </div>

                        <div class="flex w-full justify-between items-center py-3 border-t border-gray-100">
                            <span class="text-zinc-500">الهاتف</span>
                            <span class="text-black font-medium">—</span>
                        </div>
                    </div>
                @endforelse
                @include('DeliveryAgents::shop.reviews.create')
            </div>

            <!-- بطاقة تفاصيل التوصيل -->
            <div class="rounded-xl border">
                <div class="border-b bg-zinc-100 px-5 py-3 text-sm font-semibold text-black">
                    تفاصيل التوصيل
                </div>

                <div class="grid gap-2 px-5 py-4 text-sm">
                    <div class="flex w-full justify-between gap-x-5">
                        <span class="text-zinc-500">طريقة الشحن</span>
                        <span class="text-black">—</span>
                    </div>

                    <div class="flex w-full justify-between gap-x-5">
                        <span class="text-zinc-500">رقم التتبع</span>
                        <span class="text-black">—</span>
                    </div>

                    <div class="flex w-full justify-between gap-x-5">
                        <span class="text-zinc-500">حالة التوصيل</span>
                        <span class="text-black">—</span>
                    </div>

                    <div class="flex w-full justify-between gap-x-5">
                        <span class="text-zinc-500">موعد التسليم المتوقع</span>
                        <span class="text-black">—</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop::tabs.item>
@endif

