<?php

namespace Test\Yago;

use PHPUnit_Framework_TestCase;
use Yago\DataLayer;

final class DataLayerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->dataLayer = new DataLayer();
    }

    public function testPlayerCouldHaveNothing()
    {
        $this->assertSame(
            false,
            $this->dataLayer->playerHave('something')
        );
    }

    public function testReturnTrueIfCookieContainsRightValue()
    {
        $_COOKIE['something-built'] = 1;

        $this->assertSame(
            true,
            $this->dataLayer->playerHave('something')
        );
    }
}

