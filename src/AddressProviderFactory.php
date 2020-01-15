<?php

namespace Fliglio\Consul;

class AddressProviderFactory {

    protected $resolver;

    public function __construct(Resolver $dns = null) {
        $this->resolver = $dns != null ? $dns : new DnsResolver(".service.consul");
    }

    public function createConsulAddressProvider($name) {
        return $this->create($name);
    }

    public function create($name) {
        $lb = new ConsulLoadBalancer($this->resolver, new RoundRobinLoadBalancerStrategy(), $name);
        return new ConsulAddressProvider($lb);
    }
}
