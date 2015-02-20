<?php

namespace Fliglio\Consul;



class DnsResolverTest extends \PHPUnit_Framework_TestCase {


	public function testSRVLookup() {
		// given
		$expected = array(array(
			'host' => 'foo.service.fliglio.com',
			'class' => 'IN',
			'ttl' => 14382,
			'type' => 'SRV',
			'pri' => 1,
			'weight' => 1,
			'port' => 8001,
			'target' => 'foo1.fliglio.com'
		), array(
			'host' => 'foo.service.fliglio.com',
			'class' => 'IN',
			'ttl' => 14382,
			'type' => 'SRV',
			'pri' => 1,
			'weight' => 1,
			'port' => 8002,
			'target' => 'foo2.fliglio.com'
		));

		// when
		$resv = new DnsResolver();
		$found = $resv->resolve("foo.service.fliglio.com", DNS_SRV);

		// sort by target since order isn't deterministic
		usort($found, function($a, $b) {
			return strcmp($a['target'], $b['target']);
		});

		// then
		$this->assertEquals($expected[0]['target'], $found[0]['target']);
		$this->assertEquals($expected[1]['target'], $found[1]['target']);
		$this->assertEquals(count($expected), count($found));

	}

}