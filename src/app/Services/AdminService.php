<?php

namespace App\Services;

use App\Repositories\AdminRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderTripRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AdminService
{
    protected $adminRepository;
    protected $orderRepository;
    protected $orderTripRepository;
    protected $userRepository;

    public function __construct(
        AdminRepository $adminRepository,
        OrderRepository $orderRepository,
        OrderTripRepository $orderTripRepository,
        UserRepository $userRepository
    ) {
        $this->adminRepository     = $adminRepository;
        $this->orderRepository     = $orderRepository;
        $this->orderTripRepository = $orderTripRepository;
        $this->userRepository      = $userRepository;
    }

    public function findById($id, $columns = ['*'])
    {
        return $this->adminRepository->find($id, $columns);
    }

    public function login($credentials)
    {
        if (!$token = auth()->guard('admins')->attempt($credentials)) {
            return [];
        }

        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->guard('admins')->factory()->getTTL() * 60,
        ];
    }

    public function getUser()
    {
        return auth()->guard('admins')->user();
    }

    public function logout()
    {
        auth()->guard('admins')->logout();

        return auth()->guard('admins')->user();
    }

    public function getBusOperators()
    {
        return $this->adminRepository->findBusOperators();
    }
}
