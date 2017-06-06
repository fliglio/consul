<?php
namespace Fliglio\Consul;


class ConsulLoadBalancer {

	private $dns;
	private $strategy;

	public function __construct(Resolver $dns, LoadbalancerStrategy $strategy, $name) {
		$this->dns  = $dns;
		$this->strategy = $strategy;
		$this->name = $name;
	}

	public function next() {
		$addresses = $this->dns->resolve($this->name, DNS_SRV);

		$address = $this->strategy->next($addresses);
		if ($address === null) {
			throw new AddressNotAvailableException('No address to provide');
		}
		return $address;
	}
}
