<?php


namespace Fliglio\Consul;


use Fliglio\Web\UrlBuilder;

class PostFixResolver implements Resolver {

    private $hostPostFix;
    private $port;

    public function __construct($hostPostFix, $port = 443) {
        $this->hostPostFix = $hostPostFix;
        $this->port = $port;
    }

    public function resolve($name) {
        $host = str_replace('{serviceKey}', $name, $this->hostPostFix);
        $urlBuilder = (new UrlBuilder)
            ->port($this->port)
            ->scheme($this->port == 443 ? 'https' : 'http')
            ->host($host);
        return [$urlBuilder->build()];
    }
}