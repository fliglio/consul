<?php

namespace Fliglio\Consul;

class StubResolver extends DnsResolver {
	public $results;
	public function __construct(array $results) {
		$this->results = $results;
	}

	public function resolve($host, $type) {
		return $this->results;
	}

	public static function createEmptyResult() {
		$stubSrv = array();
		return new self($stubSrv);
	}

	public static function createSingleResult() {
		$stubSrv = array(array(
				'host'   => 'foo.service.consul',
				'class'  => 'IN',
				'ttl'    => 14382,
				'type'   => 'SRV',
				'pri'    => 1,
				'weight' => 1,
				'port'   => 8001,
				'target' => 'foo1.fliglio.com',
			));
		return new self($stubSrv);
	}
	public static function createDoubleResult() {
		$stubSrv = array(array(
				'host'   => 'foo.service.consul',
				'class'  => 'IN',
				'ttl'    => 14382,
				'type'   => 'SRV',
				'pri'    => 1,
				'weight' => 1,
				'port'   => 8001,
				'target' => 'foo1.fliglio.com',
			),array(
				'host'   => 'foo.service.consul',
				'class'  => 'IN',
				'ttl'    => 14382,
				'type'   => 'SRV',
				'pri'    => 1,
				'weight' => 1,
				'port'   => 8002,
				'target' => 'foo2.fliglio.com',
			));
		return new self($stubSrv);
	}
}
