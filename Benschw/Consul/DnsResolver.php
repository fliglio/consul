<?php
namespace Benschw\Consul;


class DnsResolver {
	
	public function __construct() {
	}
	
	public function resolve($name, $type) {
 		return dns_get_record($name, $type);
	}

}