<?php
namespace Fliglio\Consul;


interface LoadbalancerStrategy {
	
	public function next(array $addresses);

}