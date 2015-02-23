<?php
namespace Fliglio\Consul;

class AddressProviderFactory {

	private $dns;

	public function __construct(DnsResolver $dns = null) {
		$this->dns = $dns != null ? $dns : new DnsResolver();
	}

	public function createConsulAddressProvider($name) {
		$lb = new ConsulLoadBalancer($this->dns, new RandomLoadbalancerStrategy(), $name);
		return new ConsulAddressProvider($lb);
	}

}