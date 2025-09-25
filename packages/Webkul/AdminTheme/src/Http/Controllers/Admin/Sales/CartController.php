<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Sales;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\AdminTheme\Http\Requests\CartAddressRequest;
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
        protected CartRuleCouponRepository $cartRuleCouponRepository
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

}
