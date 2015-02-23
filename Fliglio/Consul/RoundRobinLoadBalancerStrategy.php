<?php
namespace Fliglio\Consul;


class RoundRobinLoadBalancerStrategy implements LoadbalancerStrategy {

	private $addresses;
	private $idx = null;

	public function next(array $addresses) {
		usort($addresses, function($a, $b) {
			return strcmp($a['target'], $b['target']);
		});
		$this->addresses = $addresses;

		$this->incrementIdx();

		return $this->current();
	}

	private function current() {
		if (isset($this->addresses[$this->idx])) {
			return $this->addresses[$this->idx];
		} else {
			return null;
		}
	}

	private function incrementIdx() {
		if ($this->idx === null) {
			$this->idx = rand(0, count($this->addresses)-1);
		} else {
			if ($this->idx == count($this->addresses)-1) {
				$this->idx = 0;
			} else {
				$this->idx++;
			}
		}

	}
}