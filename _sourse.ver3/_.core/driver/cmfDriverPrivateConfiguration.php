<?php


class cmfDriverPrivateConfiguration {

	private $config = array();

	public function get($n) {
		return get($this->config, $n);
	}

	protected function getAll() {
		return $this->config;
	}

	protected function set($n, $v) {
		$this->config[$n] = $v;
	}

}

?>