<?php

namespace Yago;

use Yago\Interfaces\ConfigurationInterface;
use Yago\Interfaces\PlayerInterface;

final class BuildingTree
{
    private $configuration;

    private $player;

    public function __construct(
        ConfigurationInterface $configuration,
        PlayerInterface $player
    )
    {
        $this->configuration = $configuration;
        $this->player = $player;
    }

    public function isBuildable($building)
    {
        if ($this->player->hasBuilt($building)) {
            return false;
        }

        $hierarchy = $this->configuration
            ->hierarchy();

        if (isset($hierarchy[$building])) {
            foreach ($hierarchy[$building] as $dependency => $level) {
                if (!$this->player->hasBuilt($dependency)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function buildingList()
    {
        $buildings = [];
        $buildingsFromConfiguration = $this->configuration
            ->buildings();

        foreach ($buildingsFromConfiguration as $building => $resources) {
            $buildings[] .= $building;
        }

        return $buildings;
    }
}
