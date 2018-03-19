<?php
namespace Fliglio\Consul;

class AddressProviderFactory {

	private $dns;

	public function __construct(Resolver $dns = null) {
		$this->dns = $dns != null ? $dns : new DnsResolver(".service.consul");
	}

	public function createConsulAddressProvider($name) {
		$lb = new ConsulLoadBalancer($this->dns, new RoundRobinLoadBalancerStrategy(), $name);
		return new ConsulAddressProvider($lb);
	}

	public function create($name) {
        return $this->dns->resolve($name);
    }
}
