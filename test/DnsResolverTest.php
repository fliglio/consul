<?php

namespace Benschw\Consul;


class DnsResolverTest extends \PHPUnit_Framework_TestCase {


	public function testName() {
		$resv = new DnsResolver("google.com", DNS_A);
		$addr = $resv->resolve();
		$this->assertSame('127.0.0.1', $addr);

	}

}