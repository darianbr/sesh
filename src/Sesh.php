<?php

namespace Succinct\Sesh;

use ArrayObject;
use InvalidArgumentException;
use IteratorAggregate;
use RuntimeException;

class Sesh implements IteratorAggregate
{
    /**
     * Create a new sesh.
     *
     * @param string $sessionContainerKey The session key used to store the sesh data.
     */
    public function __construct($sessionContainerKey  = 'SuccinctSeshDataStore')
    {
        if (!is_string($sessionContainerKey) || $sessionContainerKey === '') {
            throw new InvalidArgumentException('Session container key must be a string.');
        }

        if ('' === session_id()) {
            if (headers_sent()) {
                throw new RuntimeException('Failed to initialise session. Headers already sent');
            }

            if (!session_start()) {
                throw new RuntimeException('Failed to initialise session.');
            }
        }

        $this->data = array_key_exists($this->sessionContainerKey, $_SESSION)
            ? $_SESSION[$this->sessionContainerKey]
            : array();
    }

    public function __destruct()
    {
        $_SESSION[$this->sessionContainerKey] = $this->data;
        $this->data = null;
    }

    /**
     * Set a single value or an array of values.
     *
     * @param string|array $key The name of the variable, or array of key, values to set.
     *
     * @return bool If the value(s) was set correctly.
     */
    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * Get a previously set value.
     *
     * @param string $key The name of the variable to get.
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Check if a value is set.
     *
     * @param string $key The value to check.
     *
     * @return bool True if the value exists.
     */
    public function __isset($key)
    {
        if (!is_string($key) || '' === $key) {
            throw new InvalidArgumentException('Isset requires a valid string.');
        }

        return array_key_exists($key, $this->data);
    }

    /**
     * Unset a value.
     *
     * @param string $key The name of the variable to remove.
     *
     * @return bool True if the value was removed.
     */
    public function __unset($key)
    {
        return $this->remove($key);
    }

    /**
     * The session id.
     *
     * @return string The session id.
     */
    public function id()
    {
        return session_id();
    }

    /**
     * Set a single value or an array of values.
     *
     * @param string|array $key   The name of the variable, or array of key, values to set.
     * @param mixed        $value Mixed value to store.
     *
     * @return bool If the value(s) was set correctly.
     */
    public function set($key, $value = null)
    {
        if (is_string($key)) {
            $key = array($key => $value);
        }

        foreach ($key as $key => $value) {
            $this->data[$key] = $value;

            if (!array_key_exists($key, $this->data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get a previously set value.
     *
     * @param string $key The name of the variable to get.
     *
     * @return mixed
     * @throws InvalidArgumentException If an invalid key was supplied.
     */
    public function get($key)
    {
        if (!is_string($key) || '' === $key) {
            throw new InvalidArgumentException('Get must be a valid string.');
        }

        return array_key_exists($key, $this->data)
            ? $this->data[$key]
            : null;
    }

    /**
     * Remove one or more values.
     *
     * @param array|string $key The name or array of names of variables to remove.
     *
     * @return bool True if the value(s) was removed.
     */
    public function remove($key)
    {
        if (is_string($key)) {
            $key = array($key);
        }

        foreach ($key as $value) {
            $this->data[$value] = null;
            unset($this->data[$value]);

            if (array_key_exists($value, $this->data)) {
                return false;
            }
        }

        return true;
    }

    public function destroy($global = false)
    {
        $this->data = array();

        if (true === $global) {
            session_destroy();
            session_write_close();
        }
    }

    public function getIterator()
    {
        $ao = new ArrayObject($this->data);

        return $ao->getIterator();
    }

    /**
     * @var array A key, value array representing our session data.
     */
    private $data;

    /**
     * @var string They key used to store our session data against.
     */
    private $sessionContainerKey;
}
