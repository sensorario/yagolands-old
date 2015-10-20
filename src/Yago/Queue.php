<?php

namespace Yago;

use RuntimeException;
use Yago\Interfaces\Clock;

class Queue
{
    private $clock;

    private $buildingInProgress;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function addBuilding($building, array $config)
    {
        if (isset($config['buildings'][$building]['needs'])) {
            throw new RuntimeException(
                'Preconditions not satisfied'
            );
        }

        $this->buildingInProgress = $building;

        $this->clock->time();
    }

    public function buildingInProgress()
    {
        return $this->buildingInProgress;
    }
}
