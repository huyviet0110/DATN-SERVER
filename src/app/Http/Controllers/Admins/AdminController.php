<?php

namespace App\Http\Controllers\Admins;

use App\Enums\AdminTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\AdminLoginRequest;
use App\Http\Resources\Admins\OperatorCollection;
use App\Models\User;
use App\Services\AdminService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function login(AdminLoginRequest $request)
    {
        try {
            $token = $this->adminService->login($request->validated());

            if (empty($token)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_ACCOUNT_INCORRECT,
                    'Email hoặc password không chính xác!'
                );
            }

            $admin = $this->adminService->getUser();
            if (!in_array($admin->type, AdminTypeEnum::getValues())) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_ACCOUNT_INCORRECT,
                    'Email hoặc password không chính xác!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, [
                'token' => $token,
                'admin' => $admin,
            ]);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function logout()
    {
        try {
            $admin = $this->adminService->logout();

            if (!is_null($admin)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_LOGOUT,
                    'Đăng xuất thất bại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, 'Đăng xuất thành công!');
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function getProfile()
    {
        try {
            $admin = $this->adminService->getUser();

            if (is_null($admin)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Token hết hạn, vui lòng đăng nhập lại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $admin);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function getBusOperators()
    {
        try {
            $bus_operators = $this->adminService->getBusOperators();

            if ($bus_operators->isEmpty()) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Token hết hạn, vui lòng đăng nhập lại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new OperatorCollection($bus_operators));
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }
}
