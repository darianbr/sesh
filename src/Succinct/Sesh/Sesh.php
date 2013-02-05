<?php

namespace Succinct\Sesh;

class Sesh {

	protected $data = array();
	const SESSION_CONTAINER = 'session_container_key';
	
	public function __construct() {

		if ('' === session_id()) {
			if (headers_sent()) {
				throw new \RuntimeException('Failed to initialise session. Headers already sent');
			}

			if (!session_start()) {
				throw new \RuntimeException('Failed to initialise session.');
			}
		}

		$this->data = array_key_exists(self::SESSION_CONTAINER, $_SESSION) 
			? $_SESSION[self::SESSION_CONTAINER]
			: array();
	}

	public function __destruct() {

		$_SESSION[self::SESSION_CONTAINER] = $this->data;
		$this->data = NULL;
	}

	/**
	 * @param $option string|array
	 * @return bool
	 */
	public function __set($option, $value) {

		return $this->set($option, $value);
	}

	/**
	 * @param $option string
	 * @return mixed
	 */
	public function __get($option) {

		return $this->get($option);
	}

	public function __isset($option) {
		return array_key_exists($option, $this->data);
	}

	public function __unset($option) {
		return $this->remove($option);
	}

	public function id() {
		return session_id();
	}

	/**
	 * @param $option string|array
	 * @return bool
	 */
	public function set($option, $value=array()) {

		if ($option === (array) $option) {
			foreach ($option as $key => $value) {
				$this->set($key, $value);
			}
			return TRUE;
		} else {
			$this->data[$option] = $value;
			return array_key_exists($option, $this->data);
		}
	}

	/**
	 * @param $option string
	 * @return mixed
	 */
	public function get($option) {

		return array_key_exists($option, $this->data) 
			? $this->data[$option]
			: NULL;
	}

	/**
	 * @param $option string|array
	 * @return bool
	 */
	public function remove($option) {

		if ($option === (array) $option) {
			foreach ($option as $key) {
				$this->remove($key);
			}
			return TRUE;
		} else if (array_key_exists($option, $this->data)) {
			$this->data[$option] = NULL;
			unset($this->data[$option]);
			return !array_key_exists($option, $this->data);
		} else {
			return NULL;
		}
	}

	public function destroy() {

		session_destroy();
		session_write_close();
	}
}
