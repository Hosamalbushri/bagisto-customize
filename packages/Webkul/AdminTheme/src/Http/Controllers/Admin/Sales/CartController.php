<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Sales;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\AdminTheme\Http\Requests\CartAddressRequest;
use Webkul\AdminTheme\Repositories\Country\AreaRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Payment\Facades\Payment;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shipping\Facades\Shipping;

class CartController extends Controller
{
    public function __construct(
        protected CartRepository $cartRepository,
        protected CustomerRepository $customerRepository,
        protected ProductRepository $productRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository,
        protected AreaRepository $areaRepository

    ) {}

    public function storeAddress(CartAddressRequest $cartAddressRequest, int $id): JsonResource|JsonResponse
    {
        $cart = $this->cartRepository->findOrFail($id);

        $params = $cartAddressRequest->all();
        Cart::setCart($cart);

        if (Cart::hasError()) {
            return new JsonResponse([
                'message' => implode(': ', Cart::getErrors()) ?: 'Something went wrong',
            ], Response::HTTP_BAD_REQUEST);
        }
        $this->enrichAddressWithLocationData($params);

        Cart::saveAddresses($params);

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
        // معالجة عنوان الفواتير
        if (isset($params['billing']['state_area_id'])) {
            $this->enrichSingleAddress($params['billing']);
        }

        // معالجة عنوان الشحن
        if (isset($params['shipping']['state_area_id'])) {
            $this->enrichSingleAddress($params['shipping']);
        }
    }
    private function enrichSingleAddress(array &$address): void
    {
        if (!isset($address['state_area_id'])) {
            return;
        }

        try {
            // البحث عن المنطقة (نفس منطق AddressController)
            $area = $this->areaRepository->findOrFail($address['state_area_id']);


            if (!$area) {
                return;
            }

            // دمج البيانات (نفس منطق AddressController)
            $address = array_merge($address, [
                'country' => $area->country_code,
                'state'   => $area->state_code,
                'city'   => $area->area_name,
            ]);

        } catch (\Exception $e) {
            // في حالة حدوث خطأ، نستمر بدون تعديل العنوان
            \Log::warning('خطأ في إثراء بيانات العنوان: ' . $e->getMessage());
        }
    }

}
