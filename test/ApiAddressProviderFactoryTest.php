<?php

namespace Fliglio\Consul;

use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class ApiAddressProviderFactoryTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		putenv("CONSUL_HTTP_ADDR=http://127.0.0.1:8500");
	}

	public function tearDown() {
		putenv("CONSUL_HTTP_ADDR");
	}

	public function testConstructWithResolver() {
		// given
		$addr1 = "10.0.0.1";
        $addr2 = "10.0.0.2";
		$mock = new Mock([
			new Response(200, [], Stream::factory(
				sprintf(
					'[{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59fe950b5","Node":"test-svc-dev11","Address":"%1$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%1$s","wan":"%1$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service foo check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]},{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59ffefesf3","Node":"test-svc-dev12","Address":"%2$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%2$s","wan":"%2$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]}]', 
					$addr1, $addr2
				)
			))
		]);
		$guzzleClient = new \GuzzleHttp\Client();
		$guzzleClient->getEmitter()->attach($mock);
		$resolver = new ApiResolver("123.4.5.6", $guzzleClient);
		$factory = new ApiAddressProviderFactory($resolver);

		// when
		$provider = $factory->create("test");
		$url      = $provider->getAddress();
		
		// then
		$this->assertContains($url->getHost(), [$addr1, $addr2]);
	}

	public function testConstruct_withoutResolver() {
		// given
		$addr1 = "10.0.0.1";
        $addr2 = "10.0.0.2";
		$mock = new Mock([
			new Response(200, [], Stream::factory(
				sprintf(
					'[{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59fe950b5","Node":"test-svc-dev11","Address":"%1$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%1$s","wan":"%1$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service foo check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]},{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59ffefesf3","Node":"test-svc-dev12","Address":"%2$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%2$s","wan":"%2$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]}]', 
					$addr1, $addr2
				)
			))
		]);
		$guzzleClient = new \GuzzleHttp\Client();
		$guzzleClient->getEmitter()->attach($mock);
		$factory = new ApiAddressProviderFactory(null, $guzzleClient);

		// when
		$provider = $factory->create("test");
		$url      = $provider->getAddress();
		
		// then
		$this->assertContains($url->getHost(), [$addr1, $addr2]);
	}

}