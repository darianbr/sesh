<?php

namespace Succinct\Sesh;

class Sesh {
	
	public function __construct() {
		if (headers_sent()) {
			throw new \RuntimeException('Failed to initialise session. Headers already sent');
		}

		if (!session_start()) {
			throw new \RuntimeException('Failed to initialise session.');
		}

		echo 'Welcome to sesh!' . PHP_EOL;
	}

	public function set($option, $value) {

	}

	/**
	 * @param $option string|array
	 * @return bool
	 */
	public function __set($option, $value) {

	}

}
