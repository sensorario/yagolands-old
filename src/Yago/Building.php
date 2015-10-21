<?php

namespace Yago;

use Sensorario\ValueObject\ValueObject;

final class Building extends ValueObject
{
    public static function mandatory()
    {
        return [
            'wood',
            'clay',
        ];
    }

    public function secondsToBuild()
    {
        return $this->wood() + $this->clay();
    }
}
