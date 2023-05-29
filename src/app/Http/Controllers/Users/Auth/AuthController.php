<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\LoginRequest;
use App\Models\User;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    protected $userService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get a JWT token and user info via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $token = $this->userService->login($request->validated());

            if (empty($token)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_ACCOUNT_INCORRECT,
                    'Email hoặc password không chính xác!'
                );
            }

            $user = $this->userService->getUser();

            if (!$user->hasVerifiedEmail()) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_NOT_VERIFY_EMAIL,
                    'Email chưa được xác thực sau khi đăng ký thành công!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, [
                'token' => $token,
                'user'  => $user,
            ]);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    /**
     * Logout the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            $user = $this->userService->logout();

            if (!is_null($user)) {
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

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        try {
            $user = $this->userService->getUser();

            if(is_null($user)){
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Token hết hạn, vui lòng đăng nhập lại!'
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
}
