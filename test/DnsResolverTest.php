<?php

namespace Fliglio\Consul;

use Fliglio\Web\Url;

class DnsResolverTest extends \PHPUnit_Framework_TestCase {


	public function testSRVLookup() {
		// given
		$expected = array(
			Url::fromHostAndPort("foo1.fliglio.com", 8001),
			Url::fromHostAndPort("foo2.fliglio.com", 8002)
		);


		// when
		$resv = new DnsResolver(".fliglio.com");
		$found = $resv->resolve("foo");

		// sort by target since order isn't deterministic
		usort($found, function($a, $b) {
			return strcmp($a->getHost(), $b->getHost());
		});

		// then
		$this->assertEquals($expected[0]->getHost(), $found[0]->getHost());
		$this->assertEquals($expected[1]->getHost(), $found[1]->getHost());
		$this->assertEquals(count($expected), count($found));

	}

}
