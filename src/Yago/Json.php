<?php

namespace Yago;

use DateTime;

final class Json
{
    private $jsonContent = [];

    public function __construct()
    {
        if (isset($_COOKIE['village'])) {
            $this->jsonContent['village'] = $_COOKIE['village'];
        }

        if (isset($_COOKIE['username'])) {
            $this->jsonContent['username'] = $_COOKIE['username'];
        }

        if (isset($_COOKIE['building-built-at'])) {
            $now = (new DateTime('now'))->getTimestamp();
            $end = (new DateTime($_COOKIE['building-built-at']))->getTimestamp();
            $this->jsonContent['seconds-left'] = $end > $now
                ? $end - $now
                : 0;
        }
    }

    public function jsonContent()
    {
        return $this->jsonContent;
    }

    public static function toJson()
    {
        $json = new Json();

        return json_encode(
            $json->jsonContent()
        );
    }
}
