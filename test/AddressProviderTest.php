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

    public function testAlbAddressProviderFactory() {
        // given
        $alb = new HostPrefixAddressProviderFactory('us');

        // when
        $provider = $alb->create('httpstat');

        // then
        $addr = $provider->getAddress();
        $this->assertEquals([
            'host' => 'httpstat.us',
            'port' => 443
        ], [
            'host' => $addr->getHost(),
            'port' => $addr->getPort()
        ]);

        // and
        $http = new Client([
            'base_url' => $addr->__tostring(),
            'allow_redirects' => false
        ]);
        $resp = $http->get('/200');
        $this->assertEquals('200 OK', $resp->getBody()->getContents());
        $this->assertEquals('https://httpstat.us/200', $resp->getEffectiveUrl());
    }

    public function testAlbAddressProviderFactory_shouldSetSchemeHttp() {
        // given
        $alb = new HostPrefixAddressProviderFactory('google.com', 80);

        // when
        $provider = $alb->create('www');

        // then
        $addr = $provider->getAddress();
        $this->assertEquals([
            'host' => 'www.google.com',
            'port' => 80
        ], [
            'host' => $addr->getHost(),
            'port' => $addr->getPort()
        ]);

        // and
        $http = new Client([
            'base_url' => $addr->__tostring()
        ]);
        $resp = $http->get('/');
        $this->assertEquals('http://www.google.com/', $resp->getEffectiveUrl());
    }

}
