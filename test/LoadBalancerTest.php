<?php

namespace Benschw\Consul;

class LoadBalancerTest extends \PHPUnit_Framework_TestCase {

	public function testLoadBalancerLookup() {

		// given
		$expected = new Address("foo1.fliglio.com", 8001);

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

		$stubResolver = new StubResolver($stubSrv);

		// when
		$lb = new ConsulLoadBalancer($stubResolver, "foo");

		$found = $lb->next();

		// then
		$this->assertEquals($expected->getHost(), $found->getHost());
		$this->assertEquals($expected->getPort(), $found->getPort());

	}

}