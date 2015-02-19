<?php

namespace Benschw\Consul;

class StubResolver extends DnsResolver {
	public $results;
	public function __construct(array $results) {
		$this->results = $results;
	}

	public function resolve($host, $type) {
		return $this->results;
	}

}
