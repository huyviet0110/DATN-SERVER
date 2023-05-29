<?php

namespace App\Services;

use App\Repositories\BusRepository;
use Illuminate\Support\Arr;

class BusService
{
    protected $busRepository;

    public function __construct(BusRepository $busRepository)
    {
        $this->busRepository = $busRepository;
    }

    public function all()
    {
        return $this->busRepository->getAll();
    }

    public function store($inputs)
    {
        (isset($inputs['image'])) ? ($inputs['image'] = basename($inputs['image']->store('public/images/buses'))) : null;

        return $this->busRepository->update(Arr::except($inputs, $inputs['id']), $inputs['id']);
    }

    public function destroy($busId)
    {
        return $this->busRepository->delete($busId);
    }

    public function findById($busId)
    {
        return $this->busRepository->findWithoutRedirect($busId);
    }

    public function create($inputs)
    {
        (isset($inputs['image'])) ? ($inputs['image'] = basename($inputs['image']->store('public/images/buses'))) : null;

        return $this->busRepository->create($inputs);
    }
}
