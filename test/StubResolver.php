<?php

namespace Fliglio\Consul;

use Fliglio\Web\Url;

class StubResolver implements Resolver {
	public $results;
	public function __construct(array $results) {
		$this->results = $results;
	}

	public function resolve($host) {
		return $this->results;
	}

	public static function createEmptyResult() {
		$stubSrv = array();
		return new self($stubSrv);
	}

	public static function createSingleResult() {
		$stubSrv = array(
			Url::fromHostAndPort("foo1.fliglio.com", 8001)
		);
		return new self($stubSrv);
	}
	public static function createDoubleResult() {
		$stubSrv = array(
			Url::fromHostAndPort("foo1.fliglio.com", 8001),
			Url::fromHostAndPort("foo2.fliglio.com", 8002)
		);

		return new self($stubSrv);
	}
}
