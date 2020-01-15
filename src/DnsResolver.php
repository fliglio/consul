<?php

namespace Fliglio\Consul;

use Fliglio\Web\Url;

class DnsResolver implements Resolver {

	private $domain;
	
	public function __construct($domain) {
		$this->domain = $domain;
	}
	
	public function resolve($name) {
 		$record = dns_get_record($name.$this->domain, DNS_SRV);
 		$mapped = array();
 		foreach ($record as $address) {
 			$mapped[] = Url::fromHostAndPort($address['target'], $address['port']);
 		}
 		return $mapped;
	}

}
