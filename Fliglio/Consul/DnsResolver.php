<?php
namespace Fliglio\Consul;

use Fliglio\Web\Url;

class DnsResolver {
	
	public function __construct() {
	}
	
	public function resolve($name, $type) {
 		$record = dns_get_record($name, $type);
 		$mapped = array();
 		foreach ($record as $address) {
 			$mapped[] = Url::fromHostAndPort($address['target'], $address['port']);
 		}
 		return $mapped;
	}

}