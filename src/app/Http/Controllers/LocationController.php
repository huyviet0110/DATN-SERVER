<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCollection;
use App\Models\Location;
use App\Models\User;
use App\Services\LocationService;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function getLocations()
    {
        try {
            $locations = $this->locationService->getAllLocations();

            if ($locations->isEmpty()) {
                return responseErrorAPI(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    Location::ERR_GET_ALL_LOCATIONS,
                    'Hiện tại không có địa điểm nào!'
                );
            }

            return responseOkAPI(Response::HTTP_OK, new LocationCollection($locations));
        } catch (\Throwable $e) {
            return responseErrorAPI(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                User::ERR_FROM_SERVER,
                'Có lỗi từ hệ thống, vui lòng liên hệ với với quản trị viên!'
            );
        }
    }
}
