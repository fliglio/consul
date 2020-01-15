<?php

namespace Fliglio\Consul;

class RandomLoadbalancerStrategy implements LoadbalancerStrategy {
	
	public function next(array $addresses) {
		$idx = rand(0, count($addresses)-1);
		return $addresses[$idx];
	}

}