<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Journeys\CreateJourneyRequest;
use App\Http\Requests\Admins\Journeys\DeleteJourneyRequest;
use App\Http\Requests\Admins\Journeys\UpdateJourneyRequest;
use App\Http\Resources\Admins\JourneysCollection;
use App\Models\User;
use App\Services\JourneyService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class JourneysController extends Controller
{
    protected $journeyService;

    public function __construct(JourneyService $journeyService)
    {
        $this->journeyService = $journeyService;
    }

    public function index()
    {
        try {
            $journeys = $this->journeyService->all();

            if (is_null($journeys)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Hiện tại không có tuyến đường nào!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new JourneysCollection($journeys));
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function create(CreateJourneyRequest $request)
    {
        try {
            $journey = $this->journeyService->findByLocations($request->validated());

            if (!is_null($journey)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Tuyến đường này đã tồn tại rồi!'
                );
            }

            $journey = $this->journeyService->create($request->validated());

            if (is_null($journey)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_GET_PROFILE,
                    'Hiện tại không có tuyến đường nào!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $journey);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function show($journeyId)
    {
        try {
            $journey = $this->journeyService->findById($journeyId);

            if (is_null($journey)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Tuyến đường này không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $journey);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function update(UpdateJourneyRequest $request)
    {
        try {
            $journey = $this->journeyService->findByLocations($request->validated());

            if (!is_null($journey)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Tuyến đường này đã tồn tại!'
                );
            }

            $journey = $this->journeyService->store($request->validated());

            if (is_null($journey)) {
                return responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Tuyến đường cần cập nhật không tồn tại!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, $journey);
        } catch (Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }

    public function destroy(DeleteJourneyRequest $request)
    {
        try {
            return ($this->journeyService->destroy($request->validated()))
                ? responseOkAPI(Response::HTTP_OK, 'Xóa tuyến đường thành công!')
                : responseErrorAPI(
                    Response::HTTP_UNAUTHORIZED,
                    User::ERR_USER,
                    'Tuyến đường cần xóa không tồn tại!'
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
