<?php

namespace Fliglio\Consul;

use Fliglio\Web\Url;
use GuzzleHttp\Client;

class AddressProviderTest extends \PHPUnit_Framework_TestCase {

	public function testAddressProvider() {

		// given
		$expected = Url::fromHostAndPort("foo1.fliglio.com", 8001);

		$dns = StubResolver::createSingleResult();
		$lb  = new ConsulLoadBalancer($dns, new RoundRobinLoadBalancerStrategy(), "foo");

		// when
		$ap = new ConsulAddressProvider($lb);

		$found = $ap->getAddress();

		// then
		$this->assertEquals($expected->getHost(), $found->getHost());
		$this->assertEquals($expected->getPort(), $found->getPort());
	}

	public function testAddressProviderFactory() {

		// given
		$expected = Url::fromHostAndPort("foo1.fliglio.com", 8001);

		$stubResolver = StubResolver::createSingleResult();

		// when
		$fac = new AddressProviderFactory($stubResolver);

		$ap = $fac->createConsulAddressProvider("foo");

		$found = $ap->getAddress();

		// then
		$this->assertEquals($expected->getHost(), $found->getHost());
		$this->assertEquals($expected->getPort(), $found->getPort());
	}

    public function testHostPrefixAddressProviderFactory() {
        // given
        $resolver = new HostTplAddressResolver('{serviceKey}.us');
        $alb = new AddressProviderFactory($resolver);

        // when
        $provider = $alb->create('httpstat');

        // then
        $addr = $provider->getAddress();
        $this->assertEquals([
            'host' => 'httpstat.us',
            'port' => 443,
            'scheme' => 'https'
        ], [
            'host' => $addr->getHost(),
            'port' => $addr->getPort(),
            'scheme' => $addr->getScheme()
        ]);
    }

    public function testHostPrefixAddressProviderFactory_shouldSetSchemeHttp() {
        // given
        $resolver = new HostTplAddressResolver('{serviceKey}.us', 80);
        $alb = new AddressProviderFactory($resolver);

        // when
        $provider = $alb->create('httpstat');

        // then
        $addr = $provider->getAddress();
        $this->assertEquals([
            'host' => 'httpstat.us',
            'port' => 80,
            'scheme' => 'http'
        ], [
            'host' => $addr->getHost(),
            'port' => $addr->getPort(),
            'scheme' => $addr->getScheme()
        ]);
    }

}
