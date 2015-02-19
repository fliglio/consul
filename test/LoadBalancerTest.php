<?php

namespace Benschw\Consul;

class LoadBalancerTest extends \PHPUnit_Framework_TestCase {

	public function testLoadBalancerLookup() {

		// given
		$expected = new Address("foo1.fliglio.com", 8001);

		$stubResolver = StubResolver::createSingleResult();

		// when
		$lb = new ConsulLoadBalancer($stubResolver, "foo");

		$found = $lb->next();

		// then
		$this->assertEquals($expected->getHost(), $found->getHost());
		$this->assertEquals($expected->getPort(), $found->getPort());

	}

}