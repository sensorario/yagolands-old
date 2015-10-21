<?php

namespace Yago;

use DateTime;
use DateTimezone;

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
            $now = (new DateTime('now'))->setTimezone(new DateTimezone('UTC'))->format('Y-m-dTH:i:s');
            $end = (new DateTime($_COOKIE['building-built-at']))->setTimezone(new DateTimezone('UTC'))->format('Y-m-dTH:i:s');
            if ($end <= $now) {
                setcookie('building-built-at', null);
                setcookie($_COOKIE['building-in-progress'] . '-built', true, time()+3600);
            }

            $now = (new DateTime('now'))->getTimestamp();
            $end = (new DateTime($_COOKIE['building-built-at']))->getTimestamp();
            $this->jsonContent['seconds-left'] = $end >= $now
                ? $end - $now
                : 0;

            $this->jsonContent['building-built-at'] = $end >= $now
                ? $_COOKIE['building-built-at']
                : null;
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
