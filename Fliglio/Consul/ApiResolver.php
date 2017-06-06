<?php
namespace Fliglio\Consul;

use Fliglio\Web\Url;

class ApiResolver implements Resolver {

	private $addr;
	private $http;
	private $opts;

	public function __construct($addr, \GuzzleHttp\Client $http, $opts=[]) {
		$this->addr = $addr;
		$this->http = $http;
		$this->opts = $opts;
	}
	
	public function resolve($name) {
		$uri = $this->addr . '/v1/catalog/service/' . $name;

		$request = $this->http->createRequest("GET", $uri);
		$response = $this->http->send($request);
		$status = $response->getStatusCode();

		if ($status != 200) {
			throw new \Exception($status . ": " . $response->getBody());
		}

		$data = json_decode($response->getBody(), true);

		$mapped = array();
		foreach ($data as $address) {
			$url = Url::fromHostAndPort($address['ServiceAddress'], $address['ServicePort']);
			
			// if "Tags" option is set, only consider instances with matching tags
			if (isset($this->opts['Tags'])) {
				$fail = false;
				foreach ($this->opts['Tags'] as $tag) {
					if (!in_array($tag, $address['ServiceTags'])) {
						$fail = true;
					}
				}
				if (!$fail) {
					$mapped[] = $url;
				}
			} else {
				$mapped[] = $url;
			}
		}
		return $mapped;
	}

}

