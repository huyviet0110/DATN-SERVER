<?php

namespace App\Services;

use App\Repositories\JourneyRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class JourneyService
{
    protected $journeyRepository;

    public function __construct(JourneyRepository $journeyRepository)
    {
        $this->journeyRepository = $journeyRepository;
    }

    /**
     * @param  integer  $numberOfTopJourneys
     *
     * @return Collection
     */
    public function getTopJourneys($numberOfTopJourneys)
    {
        $topJourneys = $this->journeyRepository->findTopJourneysInMonth($numberOfTopJourneys);

        if ($topJourneys->count() < $numberOfTopJourneys) {
            $additionalTopJourneys = $this->journeyRepository->findJourneys($numberOfTopJourneys);

            $topJourneys = $topJourneys->concat($additionalTopJourneys);
        }

        return $topJourneys->unique(function ($item) {
            return $item['departure_location_id'] . '-' . $item['destination_location_id'];
        })->take($numberOfTopJourneys)->values();
    }

    /**
     * @param  array  $inputs
     *
     * @return Model
     */
    public function findJourneyByLocation($inputs)
    {
        return $this->journeyRepository->findByLocation($inputs);
    }

    public function all()
    {
        return $this->journeyRepository->getAll();
    }

    public function findById($journeyId)
    {
        return $this->journeyRepository->findWithoutRedirect($journeyId);
    }

    public function store($inputs)
    {
        return $this->journeyRepository->update(Arr::except($inputs, $inputs['id']), $inputs['id']);
    }

    public function destroy($inputs)
    {
        return $this->journeyRepository->delete($inputs['id']);
    }

    public function findByLocations($inputs)
    {
        return $this->journeyRepository->findJourneyByLocations($inputs);
    }

    public function create($inputs)
    {
        return $this->journeyRepository->create($inputs);
    }
}
