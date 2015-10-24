<?php

namespace Yago\Interfaces;

interface PlayerInterface
{
    public function isBuilding();

    public function hasBuilt($building);
}
