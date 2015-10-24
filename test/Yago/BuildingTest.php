<?php

namespace Test\Yago\BuildingTest;

use PHPUnit_Framework_TestCase;
use Yago\Building;

final class BuildingTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->wood = rand(0, 42);
        $this->clay = rand(0, 44);
    }

    public function testSecondsToBuildIsTheSumOfResourcesQuantity()
    {
        $building = Building::box([
            'wood' => $this->wood,
            'clay' => $this->clay,
        ]);

        $this->assertEquals(
            $this->clay + $this->wood,
            $building->secondsToBuild()
        );
    }
}

