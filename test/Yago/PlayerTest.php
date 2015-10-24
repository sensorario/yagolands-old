<?php

namespace Test\Yago;

use PHPUnit_Framework_TestCase;
use Yago\Interfaces\DataLayerInterface;
use Yago\Player;

final class PlayerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->dataLayer = $this
                ->getMockBuilder('Yago\\Interfaces\\DataLayerInterface')
            ->getMock();

        $this->player = new Player(
            $this->dataLayer
        );
    }

    public function testHasNotBuildAnything()
    {
        $this->dataLayer->expects($this->once())
            ->method('playerHave')
            ->with('something')
            ->will($this->returnValue(false));

        $this->assertSame(
            false,
            $this->player->hasBuilt('something')
        );
    }

    public function testIsBuildingOrNot()
    {
        $this->dataLayer->expects($this->once())
            ->method('playerIsBuilding')
            ->will($this->returnValue(true));

        $this->assertSame(
            true,
            $this->player->isBuilding()
        );
    }
}

