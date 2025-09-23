<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\DataGrid\Exceptions\InvalidDataGridException;
use Webkul\DeliveryAgents\Datagrids\DeliveryAgent\DeliveryAgentDataGrid;
use Webkul\DeliveryAgents\Datagrids\DeliveryAgent\Views\OrderDateGrid;
use Webkul\DeliveryAgents\Datagrids\DeliveryAgent\Views\ReviewDataGrid;
use Webkul\DeliveryAgents\Datagrids\Orders\SelectDeliveryAgentDataGrid;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;

class DeliveryAgentsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Static pagination count.
     *
     * @var int
     */
    public const COUNT = 10;

    /**
     * Orders constant for AJAX requests.
     *
     * @var string
     */
    const ORDERS = 'orders';
    const REVIEWS = 'reviews';

    /**
     * Create a new controller instance.
     *
     * @param DeliveryAgentRepository $deliveryAgentRepository
     */
    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws InvalidDataGridException
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(DeliveryAgentDataGrid::class)->process();
        }

        return view('DeliveryAgents::admin.DeliveryAgents.DeliveryAgent.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:delivery_agents,email',
            'password'      => 'required|min:6|confirmed',
            'date_of_birth' => 'date|before:today',
            'phone'         => ['unique:delivery_agents,phone', new PhoneNumber],
        ]);

        $data = array_merge([
            'password'    => bcrypt($request->password),
            'is_verified' => 1,
        ], request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
        ]));

        if (empty($data['phone'])) {
            $data['phone'] = null;
        }

        $deliveryAgent = $this->deliveryAgentRepository->create($data);

        return new JsonResponse([
            'data'    => $deliveryAgent,
            'message' => trans('deliveryAgent::app.deliveryAgent.create.create-success'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|\Illuminate\View\View
     */
    public function view(Request $request, $id)
    {
        // تحسين الأداء: استخدام eager loading مع pagination
        $deliveryAgent = $this->deliveryAgentRepository->with([
            'ranges.state_area',
            'orders' => function ($q) {
                $q->latest()->limit(10);
            },
        ])->findOrFail($id);

        if ($request->ajax()) {
            switch (request()->query('type')) {
                case self::ORDERS:
                    return datagrid(OrderDateGrid::class)->process();
                case self::REVIEWS:
                    return datagrid(ReviewDataGrid::class)->process();
                default:
                    return response()->json([
                        'data' => $deliveryAgent,
                    ]);
            }
        }

        return view('DeliveryAgents::admin.DeliveryAgents.DeliveryAgent.view', compact('deliveryAgent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'first_name'       => 'string|required',
            'last_name'        => 'string|required',
            'gender'           => 'required',
            'email'            => 'required|unique:delivery_agents,email,'.$id,
            'password'         => 'nullable|min:6|confirmed',
            'current_password' => 'required_with:password',
            'date_of_birth'    => 'nullable|date|before:today',
            'phone'            => ['unique:delivery_agents,phone,'.$id, new PhoneNumber],
            'status'           => 'required|boolean',
        ]);

        $data = request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'status',
        ]);

        if (request()->filled('password')) {
            // Fetch existing agent to verify current password
            $existingAgent = $this->deliveryAgentRepository->findOrFail($id);

            if (! Hash::check(request('current_password'), $existingAgent->password)) {
                return new JsonResponse([
                    'message' => trans('auth.password'),
                    'errors'  => [
                        'current_password' => [trans('deliveryAgent::app.deliveryAgent.edit.incorrect_current_password')],
                    ],
                ], 422);
            }

            $data['password'] = bcrypt(request('password'));
        }

        $deliveryAgent = $this->deliveryAgentRepository->update($data, $id);

        return new JsonResponse([
            'message' => trans('deliveryAgent::app.deliveryAgent.edit.edit-success'),
            'data'    => [
                'deliveryAgent' => $deliveryAgent->fresh(),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $deliveryAgent = $this->deliveryAgentRepository->find($id);

        // Prepare a robust error message (Arabic fallback if translation key is missing)
        $translationKey = 'deliveryAgent::app.deliveryAgent.delete.unsuccessful_deletion_message';
        $errorMessage = trans($translationKey);
        if ($errorMessage === $translationKey) {
            $errorMessage = 'لا يمكن حذف المندوب لوجود طلبات غير مكتملة.';
        }

        if (! $deliveryAgent) {
            return response()->json([
                'status'  => 'error',
                'message' => $errorMessage,
                'errors'  => [
                    [
                        'id'      => $id,
                        'message' => $errorMessage,
                    ],
                ],
            ], 404);
        }

        $deleted = $this->deliveryAgentRepository->deleteIfNoIncompleteOrders($id);

        if (! $deleted) {
            return response()->json([
                'status'  => 'error',
                'message' => $errorMessage,
                'errors'  => [
                    [
                        'id'      => $id,
                        'message' => $errorMessage,
                    ],
                ],
            ], 422);
        }

        return response()->json([
            'status'  => 'success',
            'message' => trans('deliveryAgent::app.deliveryAgent.delete.successful_deletion_message'),
        ]);
    }

    /**
     * Mass destroy multiple delivery agents.
     *
     * @param MassDestroyRequest $massDestroyRequest
     * @return JsonResponse
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $deliveryAgentIds = $massDestroyRequest->input('indices');

        // تحسين الأداء: تحديد حد أقصى للعناصر لتجنب تعليق الجهاز
        if (count($deliveryAgentIds) > 100) {
            return new JsonResponse([
                'status'  => 'error',
                'message' => 'لا يمكن حذف أكثر من 100 عنصر في المرة الواحدة لتجنب تعليق الجهاز.',
            ], 422);
        }

        $deliveryAgents = $this->deliveryAgentRepository->findWhereIn('id', $deliveryAgentIds);

        try {
            $deleted = [];
            $blocked = [];
            $errors = [];

            // تحسين الأداء: استخدام batch processing لتجنب تعليق الجهاز
            $batchSize = 10;
            $batches = array_chunk($deliveryAgents->toArray(), $batchSize);

            foreach ($batches as $batch) {
                foreach ($batch as $deliveryAgent) {
                    Event::dispatch('deliveryAgent.delete.before', $deliveryAgent);

                    if ($this->deliveryAgentRepository->deleteIfNoIncompleteOrders($deliveryAgent['id'])) {
                        Event::dispatch('deliveryAgent.delete.after', $deliveryAgent);
                        $deleted[] = $deliveryAgent['id'];
                    } else {
                        $blocked[] = $deliveryAgent['id'];
                        $errors[] = [
                            'id'      => $deliveryAgent['id'],
                            'message' => trans('deliveryAgent::app.deliveryAgent.delete.unsuccessful_deletion_message'),
                        ];
                    }
                }

                // إضافة تأخير صغير بين الدفعات لتجنب تعليق الجهاز
                usleep(100000); // 0.1 ثانية
            }

            // Robust error message (Arabic fallback if translation missing)
            $translationKey = 'deliveryAgent::app.deliveryAgent.dataGrid.unsuccessful_deletion_message';
            $errorMessage = trans($translationKey);
            if ($errorMessage === $translationKey) {
                $errorMessage = 'لا يمكن حذف المندوب لوجود طلبات غير مكتملة.';
            }

            if (! empty($blocked)) {
                return new JsonResponse([
                    'status'  => 'error',
                    'message' => $errorMessage,
                    'deleted' => $deleted,
                    'blocked' => $blocked,
                    'errors'  => $errors,
                ], 422);
            }

            return new JsonResponse([
                'status'  => 'success',
                'message' => trans('deliveryAgent::app.deliveryAgent.dataGrid.delete-success'),
                'deleted' => $deleted,
                'blocked' => $blocked,
                'errors'  => $errors,
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass update multiple delivery agents.
     *
     * @param MassUpdateRequest $massUpdateRequest
     * @return JsonResponse
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $selectedDeliveryAgentIds = $massUpdateRequest->input('indices');

        // تحسين الأداء: تحديد حد أقصى للعناصر لتجنب تعليق الجهاز
        if (count($selectedDeliveryAgentIds) > 100) {
            return new JsonResponse([
                'status'  => 'error',
                'message' => 'لا يمكن تحديث أكثر من 100 عنصر في المرة الواحدة لتجنب تعليق الجهاز.',
            ], 422);
        }

        try {
            // تحسين الأداء: استخدام batch processing لتجنب تعليق الجهاز
            $batchSize = 10;
            $batches = array_chunk($selectedDeliveryAgentIds, $batchSize);

            foreach ($batches as $batch) {
                foreach ($batch as $deliveryAgentId) {
                    Event::dispatch('deliveryAgent.update.before', $deliveryAgentId);

                    $deliveryAgent = $this->deliveryAgentRepository->update([
                        'status' => $massUpdateRequest->input('value'),
                    ], $deliveryAgentId);

                    Event::dispatch('deliveryAgent.update.after', $deliveryAgent);
                }

                // إضافة تأخير صغير بين الدفعات لتجنب تعليق الجهاز
                usleep(100000); // 0.1 ثانية
            }

            return new JsonResponse([
                'message' => trans('deliveryAgent::app.deliveryAgent.dataGrid.update-success'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Get selected delivery agents.
     *
     * @return JsonResponse
     */
    public function selectedDeliveryAgents()
    {
        if (request()->ajax()) {
            return datagrid(SelectDeliveryAgentDataGrid::class)->process();
        }

        abort(404);
    }
}
