<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTripRequest;
use App\Models\Order;
use App\Models\OrderTrip;
use App\Models\Trip;
use App\Models\User;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function orderTrips(OrderTripRequest $request)
    {
        try {
            $order = $this->orderService->createNewOrder($request->validated());

            if (is_null($order)) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    Order::ERR_CREATE_ORDER_FAILED,
                    'Tạo đơn hàng thất bại!'
                );
            }

            $result = $this->orderService->orderTrips($request->validated(), $order);

            $result_list = [
                Trip::ERR_TRIP_DOESNOT_EXISTS     => 'Chuyến đi này không tồn tại!',
                Trip::ERR_EXCEEDED_SEAT_REMAINING => 'Xe bạn chọn đã hết chỗ!',
                OrderTrip::ERR_CREATE_NEW_FAILED  => 'Tạo chi tiết đơn hàng thất bại!',
            ];

            if (array_key_exists($result, $result_list)) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    $result,
                    $result_list[$result]
                );
            }

            return responseOkAPI(Response::HTTP_OK, 'Đặt vé thành công, vui lòng kiểm tra email để xem chi tiết đơn đặt!');
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }
}
