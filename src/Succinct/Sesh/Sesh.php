<?php

namespace Succinct\Sesh;

class Sesh {

	protected $data = NULL;
	
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
		// TODO: Handle invalid values and arrays
		$this->data[$option] = $value;
		return TRUE;
	}

	public function get($option) {
		return array_key_exists($option, $this->data) ? $this->data[$option] : NULL;
	}

	/**
	 * @param $option string|array
	 * @return bool
	 */
	public function __set($option, $value) {
		return $this->set($option, $value);
	}

	/**
	 * @param $option string|array
	 * @return mixed
	 */
	public function __get($option) {
		return $this->get($option);
	}
}
