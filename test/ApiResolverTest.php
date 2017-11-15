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
                sprintf(
                    '[{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59fe950b5","Node":"test-svc-dev11","Address":"%1$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%1$s","wan":"%1$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service foo check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]},{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59ffefesf3","Node":"test-svc-dev12","Address":"%2$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%2$s","wan":"%2$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]}]', 
                    $addr1, $addr2
                )
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
                sprintf(
                    '[{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59fe950b5","Node":"test-svc-dev11","Address":"%1$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%1$s","wan":"%1$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"%1$s","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service foo check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]},{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59ffefesf3","Node":"test-svc-dev12","Address":"%2$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%2$s","wan":"%2$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"%2$s","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]}]', 
                    $addr1, $addr2
                )
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

    public function testResolve_returnsCorrectHostAndPort_whenTagsArePresent() {
        // given
        $addr1 = "10.0.0.1";
        $addr2 = "10.0.0.2"; // only one with fliglio service tag

        $mock = new Mock([
            new Response(200, [], Stream::factory(
                sprintf(
                    '[{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59fe950b5","Node":"test-svc-dev11","Address":"%1$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%1$s","wan":"%1$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["microsvc"],"Address":"%1$s","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service foo check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]},{"Node":{"ID":"40e4a748-2192-161a-0510-9bf59ffefesf3","Node":"test-svc-dev12","Address":"%2$s","Datacenter":"dc1","TaggedAddresses":{"lan":"%2$s","wan":"%2$s"},"Meta":{}},"Service":{"ID":"test-svc","Service":"test-svc","Tags":["fliglio","microsvc"],"Address":"%2$s","Port":8080},"Checks":[{"Node":"test-svc","CheckID":"service:foo","Name":"Service check","Status":"passing","Notes":"","Output":"","ServiceID":"test","ServiceName":"foo","ServiceTags":["primary"]}]}]', 
                    $addr1, $addr2
                )
            ))
        ]);

        $guzzleClient = new \GuzzleHttp\Client();

        $guzzleClient->getEmitter()->attach($mock);

        // when
        $resolver = new ApiResolver("123.4.5.6", $guzzleClient, ['Tags'=>['fliglio']]);

        $resp = $resolver->resolve("test-svc");

        // then
        $this->assertEquals($addr2, $resp[0]->getHost());
        $this->assertEquals("8080", $resp[0]->getPort());
    }

}