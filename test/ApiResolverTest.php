<?php

namespace Fliglio\Consul;


use Fliglio\Web\Url;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class ApiResolverTest extends \PHPUnit_Framework_TestCase {


	public function testResolve_returnsHostAndPort_whenServiceAddressEmpty() {
        // given
        $addr1 = "10.0.0.1";
        $addr2 = "10.0.0.2";

        $mock = new Mock([
			new Response(200, [], Stream::factory(
				sprintf('[{"Node":"test-svc-dev12","Address":"%s","ServiceID":"test-svc","ServiceName":"test-svc","ServiceTags":["fliglio","microsvc"],"ServiceAddress":"","ServicePort":8080},{"Node":"test-svc-dev11","Address":"%s","ServiceID":"test-svc","ServiceName":"test-svc","ServiceTags":["fliglio","microsvc"],"ServiceAddress":"","ServicePort":8080}]', $addr1, $addr2)
			))
		]);

		$guzzleClient = new \GuzzleHttp\Client();

        $guzzleClient->getEmitter()->attach($mock);

        // when
        $resolver = new ApiResolver("123.4.5.6", $guzzleClient);

        $resp = $resolver->resolve("test-svc");

        // then
        $this->assertEquals($addr1, $resp[0]->getHost());
        $this->assertEquals("8080", $resp[0]->getPort());

        $this->assertEquals($addr2, $resp[1]->getHost());
        $this->assertEquals("8080", $resp[1]->getPort());
	}

    public function testResolve_returnsHostAndPort_whenServiceAddressNotEmpty() {
        // given
        $addr1 = "10.0.0.1";
        $addr2 = "10.0.0.2";

        $mock = new Mock([
			new Response(200, [], Stream::factory(
				sprintf('[{"Node":"test-svc-dev12","Address":"123.123.123.123","ServiceID":"test-svc","ServiceName":"test-svc","ServiceTags":["fliglio","microsvc"],"ServiceAddress":"%s","ServicePort":8080},{"Node":"test-svc-dev11","Address":"123.123.123.124","ServiceID":"test-svc","ServiceName":"test-svc","ServiceTags":["fliglio","microsvc"],"ServiceAddress":"%s","ServicePort":8080}]', $addr1, $addr2)
			))
		]);

		$guzzleClient = new \GuzzleHttp\Client();

        $guzzleClient->getEmitter()->attach($mock);

        // when
        $resolver = new ApiResolver("123.4.5.6", $guzzleClient);

        $resp = $resolver->resolve("test-svc");

        // then
        $this->assertEquals($addr1, $resp[0]->getHost());
        $this->assertEquals("8080", $resp[0]->getPort());

        $this->assertEquals($addr2, $resp[1]->getHost());
        $this->assertEquals("8080", $resp[1]->getPort());
	}

}
