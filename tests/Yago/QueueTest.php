<?php

namespace Test\Yago\QueueTest;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Yaml\Yaml;
use Yago\Building;
use Yago\Queue;

final class QueueTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->yamlConfiguration = [
            'buildings' => [
                'upstream-building' => [
                    'clay' => 42,
                    'wood' => 32,
                ],
                'downstream-building' => [
                    'clay' => 42,
                    'wood' => 32,
                    'needs' => 'upstream-building'
                ],
            ]
        ];

        $this->clock = $this
            ->getMockBuilder('Yago\\Interfaces\\Clock')
            ->getMock();

        $this->queue = new Queue(
            $this->clock
        );
    }

    /**
     * @expectedException RuntimeException
     */
    public function testDoesNotAcceptDependentBuilding()
    {
        $this->clock->expects($this->never())
            ->method('time');

        $this->queue->addBuilding(
            'downstream-building',
            $this->yamlConfiguration
        );
    }

    public function testQueueMustKnowTheBuildingInProgress()
    {
        $this->queue->addBuilding(
            $buildingName = 'building' . rand(0, 42),
            $this->yamlConfiguration
        );

        $this->assertEquals(
            $buildingName,
            $this->queue->buildingInProgress()
        );
    }

    public function testClockIsCalledOnlyForBuildableBuildings()
    {
        $this->clock->expects($this->once())
            ->method('time');

        $this->queue->addBuilding(
            'upstream-building',
            $this->yamlConfiguration
        );
    }
}
