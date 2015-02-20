<?php
namespace Benschw\Consul;

use Fliglio\Web\Uri;

class ConsulLoadBalancer {

	private $dns;
	private static $consulName = ".service.consul";

	public function __construct(DnsResolver $dns, $name) {
		$this->dns  = $dns;
		$this->name = $name;
	}

	public function next() {
		$addresses = $this->dns->resolve($this->name.self::$consulName, DNS_SRV);
		// @TODO add load balancer strategy
		$address = $addresses[0];
				
		return Uri::fromHostAndPort($address['target'], $address['port']);
	}
}