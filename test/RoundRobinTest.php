<?php

namespace Fliglio\Consul;

use Fliglio\Web\Uri;

class RoundRobinTest extends \PHPUnit_Framework_TestCase {

	public function testRoundRobin() {

		// given
		$expected = array(
			Uri::fromHostAndPort("foo1.fliglio.com", 8001),
			Uri::fromHostAndPort("foo2.fliglio.com", 8002)
		);

		$stubResolver = StubResolver::createDoubleResult();

		// when
		$lb = new ConsulLoadBalancer($stubResolver, new RoundRobinLoadBalancerStrategy(), "foo");

		$foundA = array($lb->next(), $lb->next());
		$foundB = array($lb->next(), $lb->next());

		// then

		// round robin behavior
		$this->assertEquals($foundA[0]->getHost(), $foundB[0]->getHost());
		$this->assertEquals($foundA[0]->getPort(), $foundB[0]->getPort());
		$this->assertEquals($foundA[1]->getHost(), $foundB[1]->getHost());
		$this->assertEquals($foundA[1]->getPort(), $foundB[1]->getPort());

		// sort by target since order isn't deterministic
		usort($foundA, function($a, $b) {
			return strcmp($a->getHost(), $b->getHost());
		});


		// matches expected
		$this->assertEquals($expected[0]->getHost(), $foundA[0]->getHost());
		$this->assertEquals($expected[0]->getPort(), $foundA[0]->getPort());
		$this->assertEquals($expected[1]->getHost(), $foundA[1]->getHost());
		$this->assertEquals($expected[1]->getPort(), $foundA[1]->getPort());

	}

}