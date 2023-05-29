<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTripDetailRequest;
use App\Http\Requests\SearchTripRequest;
use App\Http\Resources\FilterList\BusStandCollection;
use App\Http\Resources\FilterList\DropoffPlaceCollection;
use App\Http\Resources\JourneyResource;
use App\Http\Resources\FilterList\PickupCollection;
use App\Http\Resources\FilterList\SeatTypeCollection;
use App\Http\Resources\TripDetailResource;
use App\Http\Resources\TripSearchCollection;
use App\Models\Journey;
use App\Models\Station;
use App\Models\Trip;
use App\Models\User;
use App\Services\JourneyService;
use App\Services\TripService;
use Symfony\Component\HttpFoundation\Response;

class TripController extends Controller
{
    protected $tripService;
    protected $journeyService;

    public function __construct(TripService $tripService, JourneyService $journeyService)
    {
        $this->tripService    = $tripService;
        $this->journeyService = $journeyService;
    }

    public function index(SearchTripRequest $request)
    {
        try {
            $journey = $this->journeyService->findJourneyByLocation($request->validated());

            if (is_null($journey)) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    Journey::ERR_FIND_JOURNEY,
                    'Hiện tại không có chuyến đi nào đi qua tuyến đường này!'
                );
            }

            $trips = $this->tripService->getTrips($request->validated());

            if ($trips->isEmpty()) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    Trip::ERR_SEARCH_TRIPS,
                    'Hiện tại không có chuyến đi nào phù hợp với tìm kiếm của bạn!'
                );
            }

            $data = [
                'journey' => new JourneyResource($journey),
                'trips'   => new TripSearchCollection($trips),
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

    public function getListFilter(SearchTripRequest $request)
    {
        try {
            $bus_stands = $this->tripService->findByBusStands($request->validated());

            if ($bus_stands->isEmpty()) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    Trip::ERR_FIND_BUS_STANDS,
                    'Không tìm được nhà xe nào phù hợp với tìm kiếm của bạn!'
                );
            }

            $pickup_places = $this->tripService->findByStationPlaces($request->validated(), 'pick_up_place');

            if ($pickup_places->isEmpty()) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    Trip::ERR_FIND_PICKUP_PLACES,
                    'Không tìm được điểm đón nào phù hợp với tìm kiếm của bạn!'
                );
            }

            $dropoff_places = $this->tripService->findByStationPlaces($request->validated(), 'drop_off_place');

            if ($dropoff_places->isEmpty()) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    Trip::ERR_FIND_DROPOFF_PLACES,
                    'Không tìm được các điểm trả nào phù hợp với tìm kiếm của bạn!'
                );
            }

            $seat_types = $this->tripService->findBySeatTypes($request->validated());

            if ($seat_types->isEmpty()) {
                return responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    Trip::ERR_FIND_SEAT_TYPES,
                    'Không tìm được loại ghế nào phù hợp với tìm kiếm của bạn!'
                );
            }

            $data = [
                'bus_stands'     => new BusStandCollection($bus_stands),
                'pickup_places'  => new PickupCollection($pickup_places),
                'dropoff_places' => new DropoffPlaceCollection($dropoff_places),
                'seat_types'     => new SeatTypeCollection($seat_types),
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

    public function getTripDetail(GetTripDetailRequest $request)
    {
        try {
            $data = $this->tripService->getTripDetail($request->validated());

            return (!is_null($data))
                ? responseOkAPI(Response::HTTP_OK, new TripDetailResource($data))
                : responseErrorAPI(
                    Response::HTTP_EXPECTATION_FAILED,
                    Trip::ERR_FIND_SEAT_TYPES,
                    'Chuyến đi này không tồn tại!'
                );
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }
}
