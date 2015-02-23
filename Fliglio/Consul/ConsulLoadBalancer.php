<?php
namespace Fliglio\Consul;


class ConsulLoadBalancer {

	private $dns;
	private $strategy;
	private static $consulName = ".service.consul";

	public function __construct(DnsResolver $dns, LoadbalancerStrategy $strategy, $name) {
		$this->dns  = $dns;
		$this->strategy = $strategy;
		$this->name = $name;
	}

	public function next() {
		$addresses = $this->dns->resolve($this->name.self::$consulName, DNS_SRV);

		$address = $this->strategy->next($addresses);
		if ($address === null) {
			throw new AddressNotAvailableException('No address to provide');
		}
		return $address;
	}
}