<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopJourneyCollection;
use App\Models\Journey;
use App\Models\User;
use App\Services\JourneyService;
use Symfony\Component\HttpFoundation\Response;

class JourneyController extends Controller
{
    protected $journeyService;

    public function __construct(JourneyService $journeyService)
    {
        $this->journeyService = $journeyService;
    }

    public function getTopPopular()
    {
        try {
            $topJourneys = $this->journeyService->getTopJourneys(Journey::NUMBER_OF_TOP_JOURNEYS);

            if ($topJourneys->count() < Journey::NUMBER_OF_TOP_JOURNEYS) {
                return responseErrorAPI(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    Journey::ERR_GET_TOP_JOURNEYS,
                    'Hiện tại không có chuyến đi nào hợp lệ với tìm kiếm của bạn!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new TopJourneyCollection($topJourneys));
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }
}
