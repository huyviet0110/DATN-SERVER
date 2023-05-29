<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Trips\CreateTripRequest;
use App\Http\Requests\Admins\Trips\DeleteTripRequest;
use App\Http\Requests\Admins\Trips\UpdateTripRequest;
use App\Http\Resources\Admins\TripsCollection;
use App\Models\User;
use App\Services\TripService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TripsController extends Controller
{
    protected $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    public function index()
    {
        try {
            $trips = $this->tripService->all();

            if (is_null($trips)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Hiện tại không có chuyến đi nào!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new TripsCollection($trips));
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function create(CreateTripRequest $request)
    {
        try {
            $trip = $this->tripService->create($request->validated());

            if (is_null($trip)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Thêm chuyến đi thất bại!'
                );
            }

            $station = $this->tripService->createStation($trip, $request->validated());

            if (is_null($station)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Thêm điểm đón, trả khách thất bại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $trip);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function show($tripId)
    {
        try {
            $trip = $this->tripService->findById($tripId);

            if (is_null($trip)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Chuyến đi này không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $trip);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function update(UpdateTripRequest $request)
    {
        try {
            $trip = $this->tripService->store($request->validated());

            if (is_null($trip)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Chuyến đi cần cập nhật không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $trip);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function destroy(DeleteTripRequest $request)
    {
        try {
            return ($this->tripService->destroy($request->validated()))
                ? responseOkAPI(Response::HTTP_OK, 'Xóa chuyến đi thành công!')
                : responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Chuyến đi cần xóa không tồn tại!'
                );
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Dữ liệu của chuyến đi này được sử dụng ở nhiều bảng khác nên không xóa được!'
            );
        }
    }
}
