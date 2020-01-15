<?php

namespace Fliglio\Consul;

use Fliglio\Web\Url;

interface Resolver {
    /**
     * @param $name
     * @return Url[]
     */
    public function resolve($name);
}