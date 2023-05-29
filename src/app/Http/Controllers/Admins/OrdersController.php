<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Orders\DestroyOrderRequest;
use App\Http\Requests\Admins\Orders\UpdateOrderRequest;
use App\Http\Resources\Admins\Orders\ShowOrderCollection;
use App\Http\Resources\Admins\OrdersCollection;
use App\Models\User;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrdersController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        try {
            $orders = $this->orderService->all();

            if (is_null($orders)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Hiện tại không có hóa đơn nào!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new OrdersCollection($orders));
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function show($orderId)
    {
        try {
            $order = $this->orderService->findById($orderId);

            if (is_null($order)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Hóa đơn này không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new ShowOrderCollection($order));
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function update(UpdateOrderRequest $request)
    {
        try {
            $order = $this->orderService->store($request->validated());

            if (is_null($order)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Hóa đơn cần cập nhật không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $order);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }
}
