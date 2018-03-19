<?php


namespace Fliglio\Consul;

use Fliglio\Web\UrlBuilder;

/**
 * @TODO: Return StaticAddressProvider
 */
class HostPrefixAddressProviderFactory {
    private $hostPostFix;
    private $port;

    public function __construct($hostPostFix, $port = 443) {
        $this->hostPostFix = $hostPostFix;
        $this->port = $port;
    }

    public function create($prefix) {
        $host = sprintf('%s.%s', $prefix, $this->hostPostFix);
        $urlBuilder = (new UrlBuilder)
            ->port($this->port)
            ->scheme($this->port == 443 ? 'https' : 'http')
            ->host($host);
        return new StaticAddressProvider($urlBuilder->build());
    }
}