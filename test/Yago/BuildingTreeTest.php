<?php

namespace Test\Yago;

use Yago\BuildingTree;

use PHPUnit_Framework_TestCase;

final class BuildingTreeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->player = $this
            ->getMockBuilder('Yago\\Interfaces\\PlayerInterface')
            ->getMock();

        $this->configuration = $this
            ->getMockBuilder('Yago\\Interfaces\\ConfigurationInterface')
            ->getMock();

        $this->buildingTree = new BuildingTree(
            $this->configuration,
            $this->player
        );
    }

    public function testBuildingWithoutDependenciesAreBuildable()
    {
        $this->configuration->expects($this->once())
            ->method('hierarchy')
            ->will($this->returnValue([]));

        $this->assertSame(
            true,
            $this->buildingTree->isBuildable(
                'foo'
            )
        );
    }

    public function testBuildingWithDependenciesAreNotBuildable()
    {
        $this->configuration->expects($this->once())
            ->method('hierarchy')
            ->will($this->returnValue([
                'foo' => [
                    'temple' => 1
                ]
            ]));

        $this->assertSame(
            false,
            $this->buildingTree->isBuildable(
                'foo'
            )
        );
    }

    public function testAllowBuildingWhenDependenciesAreSatisfied()
    {
        $this->configuration->expects($this->once())
            ->method('hierarchy')
            ->will($this->returnValue([
                'foo' => [
                    'temple' => 1
                ]
            ]));

        $this->player->expects($this->at(0))
            ->method('hasBuilt')
            ->will($this->returnValue(false));

        $this->player->expects($this->at(1))
            ->method('hasBuilt')
            ->with('temple')
            ->will($this->returnValue(true));

        $this->assertSame(
            true,
            $this->buildingTree->isBuildable(
                'foo'
            )
        );
    }

    public function testAllDependenciesAreChecked()
    {
        $this->configuration->expects($this->once())
            ->method('hierarchy')
            ->will($this->returnValue([
                'foo' => [
                    'temple' => 1,
                    'castle' => 1,
                ]
            ]));

        $this->player->expects($this->at(0))
            ->method('hasBuilt')
            ->will($this->returnValue(false));

        $this->player->expects($this->at(1))
            ->method('hasBuilt')
            ->with('temple')
            ->will($this->returnValue(true));

        $this->player->expects($this->at(2))
            ->method('hasBuilt')
            ->with('castle')
            ->will($this->returnValue(true));

        $this->assertSame(
            true,
            $this->buildingTree->isBuildable(
                'foo'
            )
        );
    }

    public function testDisallowBuildWhenNotAllDependenciesAreSatisfied()
    {
        $this->configuration->expects($this->once())
            ->method('hierarchy')
            ->will($this->returnValue([
                'foo' => [
                    'temple' => 1,
                    'castle' => 1,
                ]
            ]));

        $this->player->expects($this->at(0))
            ->method('hasBuilt')
            ->will($this->returnValue(false));

        $this->player->expects($this->at(1))
            ->method('hasBuilt')
            ->with('temple')
            ->will($this->returnValue(true));

        $this->player->expects($this->at(2))
            ->method('hasBuilt')
            ->with('castle')
            ->will($this->returnValue(false));

        $this->assertSame(
            false,
            $this->buildingTree->isBuildable(
                'foo'
            )
        );
    }

    public function testKnowsBuildingList()
    {
        $this->configuration->expects($this->once())
            ->method('buildings')
            ->will($this->returnValue([
                'foo' => [ 'temple' => 1, 'castle' => 1, ],
                'bar' => [ 'temple' => 1, 'castle' => 1, ],
                'baz' => [ 'temple' => 1, 'castle' => 1, ],
            ]));

        $buildingList = [
            'foo',
            'bar',
            'baz',
        ];

        $this->assertSame(
            $buildingList,
            $this->buildingTree->buildingList()
        );
    }

    public function testAlreadyBuildedBuildingIsNoMoreBuildable()
    {
        $this->player->expects($this->once())
            ->method('hasBuilt')
            ->with('foo')
            ->will($this->returnValue(true));

        $this->configuration->expects($this->never())
            ->method('hierarchy');

        $this->assertSame(
            false,
            $this->buildingTree->isBuildable(
                'foo'
            )
        );
    }
}
