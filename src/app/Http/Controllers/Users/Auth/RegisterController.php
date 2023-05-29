<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = Arr::collapse([
                $request->validated(),
                ['password' => Hash::make($request->input('password'))],
            ]);

            $user = $this->userService->register($data);

            if (is_null($user)) {
                return responseErrorAPI(
                    Response::HTTP_NO_CONTENT,
                    User::ERR_REGISTER,
                    'Đăng ký thất bai, vui lòng thử lại!'
                );
            }

            $result = $this->userService->sendVerifyEmail($user);

            if (is_null($result)) {
                return responseErrorAPI(
                    Response::HTTP_NO_CONTENT,
                    User::ERR_SEND_VERIFY_EMAIL,
                    'Gửi email xác thực thất bại, vui lòng thử lại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, 'Đăng ký thành công, hãy xác thực email để tiếp tục đăng nhập!');
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function verificationNotice()
    {
        try {
            return responseOkAPI(Response::HTTP_OK, 'Verify your email to continue login to our app');
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function verificationVerify(Request $request, $id)
    {
        try {
            if (!$request->hasValidSignature()) {
                return redirect(config('main.app_base_url') . "/register?error_message=Đường link đã bị sửa đổi!");
            }

            $user = $this->userService->findById($id);

            if ($user->hasVerifiedEmail()) {
                return redirect(config('main.app_base_url') . "/login?message=Bạn đã xác thực email rồi!");
            }

            $user->markEmailAsVerified();

            return redirect(config('main.app_base_url') . "/login?message=Xác thực email thành công!");
        } catch (Throwable $e) {
            return redirect(config('main.app_base_url') . "/register?error_message=Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!");
        }
    }
}
