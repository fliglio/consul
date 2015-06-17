<?php

namespace Fliglio\Consul;

use Fliglio\Web\Url;

class LoadBalancerTest extends \PHPUnit_Framework_TestCase {

	public function testLoadBalancerLookup() {

		// given
		$expected = Url::fromHostAndPort("foo1.fliglio.com", 8001);
		$stubResolver = StubResolver::createSingleResult();

		// when
		$lb = new ConsulLoadBalancer($stubResolver, new RoundRobinLoadBalancerStrategy(), "foo");

		$found = $lb->next();

		// then
		$this->assertEquals($expected->getHost(), $found->getHost());
		$this->assertEquals($expected->getPort(), $found->getPort());

	}


	/**
	 * @expectedException Fliglio\Consul\AddressNotAvailableException
	 */
	public function testEmptyResults() {

		// given
		$stubResolver = StubResolver::createEmptyResult();

		// when
		$lb = new ConsulLoadBalancer($stubResolver, new RoundRobinLoadBalancerStrategy(), "foo");

		$found = $lb->next();

		// then
		// exception thrown
	}


}
