<div class="flex flex-col">
    <p class="font-semibold leading-6 text-gray-800 dark:text-white">
        {{ $address->company_name ?? '' }}
    </p>

    <p class="font-semibold leading-6 text-gray-800 dark:text-white">
        {{ $address->name }}
    </p>

    @if ($address->vat_id)
        <p class="font-semibold leading-6 text-gray-800 dark:text-white">
            {{ $address->vat_id }}
        </p>
    @endif

    <p class="!leading-6 text-gray-600 dark:text-gray-300 flex flex-wrap gap-x-1">
        <span class="after:content-[',']">{{ core()->country_name($address->country)}} </span>
        <span class="after:content-[',']">{{myHelper()->state_name($address->state) }}</span>
        <span class="after:content-[',']">{{ $address->city }}</span>
        <span >{{ $address->address }}</span>
        @if ($address->postcode)
        <span class="before:content-[',']">
            , ({{ $address->postcode }})
        </span>
        @endif

    </p>
    <p class="!leading-6 text-gray-600 dark:text-gray-300 flex flex-wrap gap-x-1">
        <span>{{ __('admin::app.sales.orders.view.contact') }}: {{ $address->phone }}</span>
    </p>


</div>
