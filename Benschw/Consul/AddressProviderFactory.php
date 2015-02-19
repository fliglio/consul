<?php
namespace Benschw\Consul;


class AddressProviderFactory {
	
	private $dns

	public function __construct(DnsResolver $dns) {
		$this->dns = $dns;
	}
	
	public function getConsulAddressProvider($name) {
		$lb = new ConsulLoadBalancer($this->dns, $name);
		return new ConsulAddressProvider($lb)
	}

}