<?php

namespace Yago;

use Symfony\Component\Yaml\Yaml;

final class Configuration implements Interfaces\ConfigurationInterface
{
    public function __construct()
    {
        $yaml = file_get_contents(
            __DIR__ . '/../../app/config/buildings.yml'
        );

        $this->conf = Yaml::parse($yaml);
    }

    public function hierarchy()
    {
        return $this->conf['buildings']['hierarchies'];
    }

    public function buildings()
    {
        return $this->conf['buildings']['resources'];
    }
}
