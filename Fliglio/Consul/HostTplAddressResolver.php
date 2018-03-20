<?php


namespace Fliglio\Consul;


use Fliglio\Web\UrlBuilder;

class HostTplAddressResolver implements Resolver {

    private $hostTemplate;
    private $port;

    public function __construct($hostTemplate, $port = 443) {
        $this->hostTemplate = $hostTemplate;
        $this->port = $port;
    }

    public function resolve($name) {
        $host = str_replace('{serviceKey}', $name, $this->hostTemplate);
        $urlBuilder = (new UrlBuilder)
            ->port($this->port)
            ->scheme($this->port == 443 ? 'https' : 'http')
            ->host($host);
        return [$urlBuilder->build()];
    }
}