<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Buses\CreateBusRequest;
use App\Http\Requests\Admins\Buses\DeleteBusRequest;
use App\Http\Requests\Admins\Buses\UpdateBusRequest;
use App\Http\Resources\Admins\Buses\BusesCollection;
use App\Models\User;
use App\Services\BusService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BusController extends Controller
{
    protected $busService;

    public function __construct(BusService $busService)
    {
        $this->busService = $busService;
    }

    public function index()
    {
        try {
            $buses = $this->busService->all();

            if (is_null($buses)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Hiện tại không có xe khách nào!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new BusesCollection($buses));
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function create(CreateBusRequest $request)
    {
        try {
            $bus = $this->busService->create($request->validated());

            if (is_null($bus)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Thêm xe khách thất bại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $bus);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function show($busId)
    {
        try {
            $bus = $this->busService->findById($busId);

            if (is_null($bus)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Xe khách này không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $bus);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function update(UpdateBusRequest $request)
    {
        try {
            $bus = $this->busService->store($request->validated());

            if (is_null($bus)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Xe khách cần cập nhật không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $bus);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function destroy(DeleteBusRequest $request)
    {
        try {
            return ($this->busService->destroy($request->validated()))
                ? responseOkAPI(Response::HTTP_OK, 'Xóa xe khách thành công!')
                : responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Xe khách cần xóa không tồn tại!'
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
