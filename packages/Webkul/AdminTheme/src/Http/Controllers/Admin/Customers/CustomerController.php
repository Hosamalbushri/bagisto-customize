<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Customers;

use Webkul\Admin\DataGrids\Customers\View\InvoiceDataGrid;
use Webkul\Admin\DataGrids\Customers\View\ReviewDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\AdminTheme\Datagrids\Customers\CustomerDataGrid;
use Webkul\AdminTheme\Datagrids\Customers\View\OrderDataGrid;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerNoteRepository;
use Webkul\Customer\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    /**
     * Ajax request for orders.
     */
    public const ORDERS = 'orders';

    /**
     * Ajax request for invoices.
     */
    public const INVOICES = 'invoices';

    /**
     * Ajax request for reviews.
     */
    public const REVIEWS = 'reviews';

    /**
     * Static pagination count.
     *
     * @var int
     */
    public const COUNT = 10;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected CustomerNoteRepository $customerNoteRepository
    ) {}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(CustomerDataGrid::class)->process();
        }
        abort(404);
    }
    public function show(int $id)
    {

        if (request()->ajax()) {
            switch (request()->query('type')) {
                case self::ORDERS:
                    return datagrid(OrderDataGrid::class)->process();

                case self::INVOICES:
                    return datagrid(InvoiceDataGrid::class)->process();

                case self::REVIEWS:
                    return datagrid(ReviewDataGrid::class)->process();
            }
        }
        abort(404);
    }

}
