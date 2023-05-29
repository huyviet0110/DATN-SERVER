<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Enums\TicketStatusEnum;
use App\Jobs\VerifyEmailJob;
use App\Models\Order;
use App\Models\OrderTrip;
use App\Repositories\BaseRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderTripRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $userRepository;
    protected $orderRepository;
    protected $orderTripRepository;

    public function __construct(UserRepository $userRepository, OrderRepository $orderRepository, OrderTripRepository $orderTripRepository)
    {
        $this->userRepository      = $userRepository;
        $this->orderRepository     = $orderRepository;
        $this->orderTripRepository = $orderTripRepository;
    }

    public function register($data)
    {
        return $this->userRepository->create($data);
    }

    public function sendVerifyEmail($user)
    {
        return VerifyEmailJob::dispatchAfterResponse($user);
    }

    public function findById($id, $columns = ['*'])
    {
        return $this->userRepository->findWithoutRedirect($id, $columns);
    }

    public function login($credentials)
    {
        if (!$token = auth()->attempt($credentials)) {
            return [];
        }

        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ];
    }

    public function getUser()
    {
        return auth()->user();
    }

    public function logout()
    {
        auth()->logout();

        return auth()->user();
    }

    /**
     * @param  array  $inputs
     *
     * @return boolean|string
     */
    public function cancelTicket($inputs)
    {
        $ticket = $this->orderTripRepository->findTicketCanBeCancel($inputs['ticket_id']);
        if (is_null($ticket)) {
            return Order::ERR_TICKET_NEED_CANCEL_IS_INVALID;
        }

        $is_updated = $this->orderTripRepository->updateStatus($inputs['ticket_id'], TicketStatusEnum::CANCELED);
        if (!$is_updated) {
            return Order::ERR_UPDATE_TICKET_STATUS;
        }
        $is_updated_total_payment = $this->orderRepository->updateTotalPayment($inputs['order_id'], $ticket);
        if (!$is_updated_total_payment) {
            return Order::ERR_UPDATE_TOTAL_PAYMENT;
        }

        $ticket_remaining = $this->orderTripRepository->getNumberTicketRemaining($inputs['order_id']);
        if ($ticket_remaining === 0) {
            $is_status_updated = $this->orderRepository->updateStatus($inputs['order_id'], OrderStatusEnum::CANCELED);
            if (!$is_status_updated) {
                return Order::ERR_UPDATE_ORDER_STATUS;
            }
        }

        return true;
    }

    /**
     * @param  array  $inputs
     *
     */
    public function updateProfile($inputs, $request)
    {
        if(isset($inputs['avatar'])) {
            $inputs['avatar'] = basename($request->file('avatar')->store('public/images'));
            return $this->userRepository->update($inputs, auth()->id());
        } else {
            return $this->userRepository->update(Arr::except($inputs, ['avatar']), auth()->id());
        }
    }

    /**
     * @param  integer  $status
     *
     * @return Collection
     */
    public function getListOrdersByStatus($status)
    {
        return $this->orderRepository->findOrderByStatus($status);
    }

    /**
     * @param  integer  $orderTripId
     *
     * @return Model|null
     */
    public function getOrderDetail($orderTripId)
    {
        return $this->orderTripRepository->findOrderDetail($orderTripId);
    }

    /**
     * @param  integer  $orderId
     *
     * @return Model|null
     */
    public function getListOrderTrips($orderId)
    {
        return $this->orderRepository->findOrderTrips($orderId);
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function store($userData)
    {
        (isset($userData['avatar'])) ? ($userData['avatar'] = basename($userData['avatar']->store('public/images'))) : null;

        return $this->userRepository->update(Arr::except($userData, $userData['id']), $userData['id']);
    }

    public function destroy($userId)
    {
        return $this->userRepository->delete($userId);
    }
}
