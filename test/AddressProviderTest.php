<?php

namespace Fliglio\Consul;

use Fliglio\Web\Uri;

class AddressProviderTest extends \PHPUnit_Framework_TestCase {

	public function testAddressProvider() {

		// given
		$expected = Uri::fromHostAndPort("foo1.fliglio.com", 8001);

		$dns = StubResolver::createSingleResult();
		$lb  = new ConsulLoadBalancer($dns, "foo");

		// when
		$ap = new ConsulAddressProvider($lb);

		$found = $ap->getAddress();

		// then
		$this->assertEquals($expected->getHost(), $found->getHost());
		$this->assertEquals($expected->getPort(), $found->getPort());
	}

	public function testAddressProviderFactory() {

		// given
		$expected = Uri::fromHostAndPort("foo1.fliglio.com", 8001);

		$stubResolver = StubResolver::createSingleResult();

		// when
		$fac = new AddressProviderFactory($stubResolver);

		$ap = $fac->createConsulAddressProvider("foo");

		$found = $ap->getAddress();

		// then
		$this->assertEquals($expected->getHost(), $found->getHost());
		$this->assertEquals($expected->getPort(), $found->getPort());
	}

}