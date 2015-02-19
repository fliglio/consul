<?php
namespace Benschw\Consul;


class ConsulAddressProvider implements AddressProvider {
	
	protected $lb;
	
	public function __construct(ConsulLoadBalanacer $lb) {
		$this->lb = $lb;
	}
	
	public function getAddress() {
		return $this->lb->next();
	}

}