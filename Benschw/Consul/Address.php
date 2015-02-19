<?php
namespace Benschw\Consul;


class Address {
	
	private $host;
	private $port;

	public function __construct($host, $port) {
		$this->host = $host;
		$this->port = $port;
	}
	
	public function getHost() {
		return $this->host;
	}
	public function getPort() {
		return $this->port;
	}

}