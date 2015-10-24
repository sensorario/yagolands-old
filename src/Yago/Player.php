<?php

namespace Yago;

use Yago\Interfaces\DataLayerInterface;

final class Player implements Interfaces\PlayerInterface
{
    private $dataLayer;

    public function __construct(
        DataLayerInterface $dataLayer
    )
    {
        $this->dataLayer = $dataLayer;
    }

    public function isBuilding()
    {
        return $this->dataLayer
            ->playerIsBuilding();
    }

    public function hasBuilt($building)
    {
        return $this->dataLayer
            ->playerHave($building);
    }
}
