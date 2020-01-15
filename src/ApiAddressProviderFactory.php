<?php

namespace Fliglio\Consul;

use GuzzleHttp\Client;

class ApiAddressProviderFactory extends AddressProviderFactory {

	public function __construct(Resolver $resolver = null, Client $client = null) {
		if (is_null($resolver)) {
			$baseUri = 'http://127.0.0.1:8500';
			if (getenv('CONSUL_HTTP_ADDR') !== false) {
				$baseUri = getenv('CONSUL_HTTP_ADDR');
			}
			$options = [
				'http_errors' => false,
			];
			
			if (is_null($client)) {
				$client = new Client($options);
			}
			
			$resolver = new ApiResolver($baseUri, $client, ['Stale' => true]);
		}
		$this->resolver = $resolver;
	
	}
}