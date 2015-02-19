<?php
namespace Benschw\Consul;


class DnsResolver {
	
	protected $name;
	protected $type;
	
	public function __construct($name, $type) {
		$this->name = $name;
		$this->type = $type;
	}
	
	public function resolve() {
 		return dns_get_record($this->name, $this->type);
	}

}