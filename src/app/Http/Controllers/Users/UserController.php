<?php

namespace App\Http\Controllers\Users;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\DeleteUserRequest;
use App\Http\Requests\Users\GetOrderTripsRequest;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Admins\UsersCollection;
use App\Http\Resources\ListOrders\ListOrdersCollection;
use App\Http\Requests\CancelOrderRequest;
use App\Http\Resources\ListOrders\ListOrderTripsCollection;
use App\Http\Resources\ListOrders\ListOrderTripsResource;
use App\Models\Order;
use App\Http\Requests\Users\GetOrderDetailRequest;
use App\Http\Resources\ListOrders\OrderDetailResource;
use App\Models\OrderTrip;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function cancelTicket(CancelOrderRequest $request)
    {
        try {
            $result = $this->userService->cancelTicket($request->validated());

            if (array_key_exists($result, Order::ERR_WHEN_CANCEL_TICKET)) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    $result,
                    Order::ERR_WHEN_CANCEL_TICKET[$result]
                );
            }

            return responseOkAPI(Response::HTTP_OK, 'Hủy vé thành công, vui lòng kiểm tra email để xem chi tiết đơn đặt!');
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = $this->userService->updateProfile($request->validated(), $request);

            return (!is_null($user))
                ? responseOkAPI(Response::HTTP_OK, $user)
                : responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    User::ERR_UPDATE_PROFILE,
                    'Cập nhật thông tin người dùng thất bại!');
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function getListOrders()
    {
        try {
            $data = [
                'list_orders_new'          => new ListOrdersCollection($this->userService->getListOrdersByStatus(OrderStatusEnum::NEW)),
                'list_orders_already_paid' => new ListOrdersCollection($this->userService->getListOrdersByStatus(OrderStatusEnum::ALREADY_PAID)),
                'list_orders_cancelled'    => new ListOrdersCollection($this->userService->getListOrdersByStatus(OrderStatusEnum::CANCELED)),
                'list_orders_failed'       => new ListOrdersCollection($this->userService->getListOrdersByStatus(OrderStatusEnum::PAYMENT_FAILED)),
            ];

            return responseOkAPI(Response::HTTP_OK, $data);
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function getListOrderTrips(GetOrderTripsRequest $request)
    {
        try {
            $data = $this->userService->getListOrderTrips($request->validated());

            if (is_null($data)) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    User::ERR_ORDER_NOT_EXISTS,
                    'Đơn đặt vé này không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new ListOrderTripsResource($data));
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function getOrderDetail(GetOrderDetailRequest $request)
    {
        try {
            $order_detail = $this->userService->getOrderDetail($request->validated());

            return ($order_detail)
                ? responseOkAPI(Response::HTTP_OK, new OrderDetailResource($order_detail))
                : responseErrorAPI(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    OrderTrip::ERR_FIND_ORDER_DETAIL,
                    'Không tìm thấy vé nào trùng khớp với vé cần tìm!'
                );
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function index()
    {
        try {
            $list_users = $this->userService->getAllUsers();

            if (is_null($list_users)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Hiện tại không có users nào!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new UsersCollection($list_users));
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function show($userId)
    {
        try {
            $user = $this->userService->findById($userId);

            if (is_null($user)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'User này không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $user);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            $user = $this->userService->store($request->validated());

            if (is_null($user)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'User cần cập nhật không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $user);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function destroy(DeleteUserRequest $request)
    {
        try {
            return ($this->userService->destroy($request->validated()))
                ? responseOkAPI(Response::HTTP_OK, 'Xóa user thành công!')
                : responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'User cần xóa không tồn tại!'
                );
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }
}
