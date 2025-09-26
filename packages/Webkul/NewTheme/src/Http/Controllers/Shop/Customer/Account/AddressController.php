<?php

namespace Webkul\NewTheme\Http\Controllers\Shop\Customer\Account;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\AdminTheme\Repositories\Country\AreaRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\NewTheme\Http\Requests\Customer\AddressRequest;
use Webkul\Shop\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function __construct(
        protected CustomerAddressRepository $customerAddressRepository,
        protected AreaRepository $areaRepository
    ) {}

    /**
     * Create a new address for customer.
     *
//     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AddressRequest $request)
    {
        $customer = auth()->guard('customer')->user();

        Event::dispatch('customer.addresses.create.before');
        $area = $this->areaRepository->findOrFail($request->get('state_area_id'));
        if (! $area) {
            return new JsonResponse([
                'message' => trans('adminTheme::app.shop.customers.customers.view.address.area-not-found'),
                'status'  => 'error',
            ]);
        }

        $data = array_merge(request()->only([
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
            'customer_id' => $customer->id,
            'address'     => implode(PHP_EOL, array_filter($request->input('address'))),
            'country'     => $area->country_code,
            'state'       => $area->state_code,
            'city'        => $area->area_name,
        ]);

        if (! empty($data['default_address'])) {
            $this->customerAddressRepository->where('customer_id', $data['customer_id'])
                ->where('default_address', 1)
                ->update(['default_address' => 0]);
        }

        $customerAddress = $this->customerAddressRepository->create($data);

        Event::dispatch('customer.addresses.create.after', $customerAddress);

        session()->flash('success', trans('shop::app.customers.account.addresses.index.create-success'));

        return redirect()->route('shop.customers.account.addresses.index');
    }

    /**
     * Edit's the pre-made resource of customer called Address.
     *
//     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, AddressRequest $request)
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer->addresses()->find($id)) {
            session()->flash('warning', trans('shop::app.customers.account.addresses.index.security-warning'));

            return redirect()->route('shop.customers.account.addresses.index');
        }
        $area = $this->areaRepository->findOrFail($request->get('state_area_id'));
        if (! $area) {
            return new JsonResponse([
                'message' => trans('adminTheme::app.shop.customers.customers.view.address.area-not-found'),
                'status'  => 'error',
            ]);
        }

        Event::dispatch('customer.addresses.update.before', $id);

        $data = array_merge(request()->only([
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
            'customer_id' => $customer->id,
            'address'     => implode(PHP_EOL, array_filter($request->input('address'))),
            'country'     => $area->country_code,
            'state'       => $area->state_code,
            'city'        => $area->area_name,
        ]);

        $customerAddress = $this->customerAddressRepository->update($data, $id);

        Event::dispatch('customer.addresses.update.after', $customerAddress);

        session()->flash('success', trans('shop::app.customers.account.addresses.index.edit-success'));

        return redirect()->route('shop.customers.account.addresses.index');
    }
}
