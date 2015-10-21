<?php

namespace Yago;

final class Status
{
    public static function userCanBuild()
    {
        return isset($_COOKIE['username'])
            && !isset($_COOKIE['building-built-at'])
            && !isset($_COOKIE['temple-built']);
    }
}
