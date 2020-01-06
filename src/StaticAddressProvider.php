<?php
namespace Fliglio\Consul;

use Fliglio\Web\Url;

class StaticAddressProvider implements AddressProvider {

	protected $address;

	public function __construct(Url $address) {
		$this->address = $address;
	}

	public function getAddress() {
		return $this->address;
	}

}

