<?php

namespace Fliglio\Consul;

class ConsulAddressProvider implements AddressProvider {

	protected $lb;

	public function __construct(ConsulLoadBalancer $lb) {
		$this->lb = $lb;
	}

	public function getAddress() {
		return $this->lb->next();
	}

}