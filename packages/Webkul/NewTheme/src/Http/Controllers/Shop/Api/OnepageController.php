<?php

namespace Webkul\NewTheme\Http\Controllers\Shop\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\AdminTheme\Repositories\Country\AreaRepository;
use Webkul\Checkout\Facades\Cart;
use Webkul\NewTheme\Http\Requests\CartAddressRequest;
use Webkul\Payment\Facades\Payment;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Shop\Http\Controllers\API\APIController;

class OnepageController extends APIController
{
    public function __construct(
        protected AreaRepository $areaRepository

    ) {}

    /**
     * Store address.
     */
    public function storeAddress(CartAddressRequest $cartAddressRequest): JsonResource
    {
        $params = $cartAddressRequest->all();
        $this->enrichAddressWithLocationData($params);

        if (
            ! auth()->guard('customer')->check()
            && ! Cart::getCart()->hasGuestCheckoutItems()
        ) {
            return new JsonResource([
                'redirect' => true,
                'data'     => route('shop.customer.session.index'),
            ]);
        }

        if (Cart::hasError()) {
            return new JsonResource([
                'redirect'     => true,
                'redirect_url' => route('shop.checkout.cart.index'),
            ]);
        }

        Cart::saveAddresses($params);

        $cart = Cart::getCart();

        Cart::collectTotals();

        if ($cart->haveStockableItems()) {
            if (! $rates = Shipping::collectRates()) {
                return new JsonResource([
                    'redirect'     => true,
                    'redirect_url' => route('shop.checkout.cart.index'),
                ]);
            }

            return new JsonResource([
                'redirect' => false,
                'data'     => $rates,
            ]);
        }

        return new JsonResource([
            'redirect' => false,
            'data'     => Payment::getSupportedPaymentMethods(),
        ]);
    }

    private function enrichAddressWithLocationData(array &$params): void
    {
        if (isset($params['billing']['state_area_id'])) {
            $this->enrichSingleAddress($params['billing']);
        }

        if (isset($params['shipping']['state_area_id'])) {
            $this->enrichSingleAddress($params['shipping']);
        }
    }

    private function enrichSingleAddress(array &$address): void
    {
        if (! isset($address['state_area_id'])) {
            return;
        }

        try {
            $area = $this->areaRepository->findOrFail($address['state_area_id']);

            if (! $area) {
                return;
            }

            $address = array_merge($address, [
                'country' => $area->country_code,
                'state'   => $area->state_code,
                'city'    => $area->area_name,
            ]);

        } catch (\Exception $e) {
            // في حالة حدوث خطأ، نستمر بدون تعديل العنوان
            \Log::warning('خطأ في إثراء بيانات العنوان: '.$e->getMessage());
        }
    }
}
