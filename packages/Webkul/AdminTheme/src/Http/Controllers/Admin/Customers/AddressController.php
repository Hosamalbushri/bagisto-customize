<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\AdminTheme\Http\Requests\AddressRequest;
use Webkul\AdminTheme\Http\Resources\AddressResource;
use Webkul\AdminTheme\Repositories\Country\AreaRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\CustomerRepository;

class AddressController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerAddressRepository $customerAddressRepository,
        protected AreaRepository $areaRepository
    ) {}

    public function store(int $id, AddressRequest $request): JsonResponse
    {
        $area = $this->areaRepository->findOrFail($request->get('state_area_id'));
        if (! $area) {
            return new JsonResponse([
                'message' => trans('adminTheme::app.shop.customers.customers.view.address.area-not-found'),
                'status'  => 'error',
            ]);
        }
        $data = array_merge($request->only([
            'customer_id',
            'company_name',
            'vat_id',
            'first_name',
            'last_name',
            'address',
            'postcode',
            'phone',
            'email',
            'default_address',
            'state_area_id',
        ]), [
            'address'     => implode(PHP_EOL, array_filter(request()->input('address'))),
            'country'     => $area->country_code,
            'state'       => $area->state_code,
            'city'        => $area->area_name,
        ]);

        Event::dispatch('customer.addresses.create.before');

        if (! empty($data['default_address'])) {
            $this->customerAddressRepository->where('customer_id', $data['customer_id'])
                ->where('default_address', 1)
                ->update(['default_address' => 0]);
        }

        $address = $this->customerAddressRepository->create(array_merge($data, [
            'customer_id' => $id,
        ]));

        Event::dispatch('customer.addresses.create.after', $address);

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.view.address.create-success'),
            'data'    => new AddressResource($address),
        ]);
    }

    /**
     * Edit's the pre made resource of customer called address.
     */
    public function update(int $id, AddressRequest $request): JsonResponse
    {
        $area = $this->areaRepository->findOrFail($request->get('state_area_id'));
        if (! $area) {
            return new JsonResponse([
                'message' => trans('adminTheme::app.shop.customers.customers.view.address.area-not-found'),
                'status'  => 'error',
            ]);
        }
        $data = array_merge($request->only([
            'customer_id',
            'company_name',
            'vat_id',
            'first_name',
            'last_name',
            'address',
            'postcode',
            'phone',
            'email',
            'default_address',
            'state_area_id',
        ]), [
            'address'     => implode(PHP_EOL, array_filter(request()->input('address'))),
            'country'     => $area->country_code,
            'state'       => $area->state_code,
            'city'        => $area->area_name,
        ]);

        Event::dispatch('customer.addresses.update.before', $id);

        if (! empty($data['default_address'])) {
            $this->customerAddressRepository->where('customer_id', $data['customer_id'])
                ->where('default_address', 1)
                ->update(['default_address' => 0]);
        }

        $address = $this->customerAddressRepository->update($data, $id);

        Event::dispatch('customer.addresses.update.after', $address);

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.view.address.update-success'),
            'data'    => new AddressResource($address),
        ]);
    }
}
