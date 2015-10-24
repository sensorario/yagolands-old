<?php

namespace Yago;

final class DataLayer implements Interfaces\DataLayerInterface
{
    public function playerIsBuilding()
    {
        return isset($_COOKIE['building-built-at']);
    }

    public function playerHave($building)
    {
        if (isset($_COOKIE[$building . '-built'])) {
            return true;
        }

        return false;
    }
}
