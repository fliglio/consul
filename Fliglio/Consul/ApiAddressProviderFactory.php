<?php
namespace Fliglio\Consul;

class ApiAddressProviderFactory {

	private $resolver;

	public function __construct(Resolver $resolver = null) {
		if (is_null($resolver)) {
			$baseUri = 'http://127.0.0.1:8500';
			if (getenv('CONSUL_HTTP_ADDR') !== false) {
				$baseUri = getenv('CONSUL_HTTP_ADDR');
			}
			$options = [
				'http_errors' => false,
			];
			$http = new \GuzzleHttp\Client($options);
			$resolver = new ApiResolver($baseUri, $http, ['Stale' => true]);
		}
		$this->resolver = $resolver;
	}

	public function create($name) {
		$lb = new ConsulLoadBalancer($this->resolver, new RoundRobinLoadBalancerStrategy(), $name);
		return new ConsulAddressProvider($lb);
	}
}

