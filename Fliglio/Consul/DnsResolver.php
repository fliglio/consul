<?php
namespace Fliglio\Consul;

use Fliglio\Web\Uri;

class DnsResolver {
	
	public function __construct() {
	}
	
	public function resolve($name, $type) {
 		$record = dns_get_record($name, $type);
 		$mapped = array();
 		foreach ($record as $address) {
 			$mapped[] = Uri::fromHostAndPort($address['target'], $address['port']);
 		}
 		return $mapped;
	}

}