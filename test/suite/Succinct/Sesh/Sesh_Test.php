<?php

use Succinct\Sesh\Sesh;

/**
 * @runInSeparateProcess
 */
class SeshTest extends PHPUnit_Framework_TestCase {

	protected $sesh = NULL;

	protected function setUp() {
		parent::setUp();

		$this->sesh = new Sesh();
	}

	public function testId() {
		$this->assertTrue('' !== $this->sesh->id());
	}

	public function testSet() {
		$bool = $this->sesh->set('foo', 'bar');
		$this->assertTrue($bool);
	}

	public function test_Set() {
		$this->sesh->foo = 'bar';
		$this->assertSame($this->sesh->foo, 'bar');
	}

	public function testSetArray() {
		$bool = $this->sesh->set(array('foo' => 'oof', 'bar' => 'rab', 'baz' => 'zab'));
		$this->assertTrue($bool);
	}

	public function testGet() {
		$value = $this->sesh->get('foo');
		$this->assertNull($value);

		$value = $this->sesh->set('foo', 'bar');
		$value = $this->sesh->get('foo');
		$this->assertSame($value, 'bar');

		// make sure non-existant variable are returned as null
		$this->assertNull($this->sesh->get('someInvalidVariable'));
	}

	public function test_Get() {
		$value = $this->sesh->foo;
		$this->assertNull($value);

		$value = $this->sesh->foo = 'bar';
		$value = $this->sesh->foo;
		$this->assertSame($value, 'bar');

		// make sure non-existant variable are returned as null
		$this->assertNull($this->sesh->someInvalidVariable);
	}
	
	/**
	 * There is no get as array feature, but we want to test that the set with array method
	 * 	is doing its jobs correctly
     */
	public function testGetArray() {
		$value = $this->sesh->get('foo');
		$this->assertNull($value);

		$bool = $this->sesh->set(array('foo' => 'oof', 'bar' => 'rab', 'baz' => 'zab'));

		$value = $this->sesh->get('foo');
		$this->assertSame($value, 'oof');

		$value = $this->sesh->get('bar');
		$this->assertSame($value, 'rab');

		$value = $this->sesh->get('baz');
		$this->assertSame($value, 'zab');
	}

	public function test_Isset() {
		$this->sesh->foo = 'bar';
		
		$this->assertTrue(isset($this->sesh->foo));
		$this->assertTrue(!empty($this->sesh->foo));

		$this->assertFalse(isset($this->sesh->someInvalidVariable));
		$this->assertTrue(empty($this->sesh->someInvalidVariable));
	}

	public function test_Unset() {
		$this->sesh->foo = 'bar';

		unset($this->sesh->foo);
		
		$this->assertFalse(isset($this->sesh->foo));
		$this->assertTrue(empty($this->sesh->foo));
	}

	public function testRemove() {
		$this->sesh->foo = 'bar';

		$done = $this->sesh->remove('foo');

		$this->assertTrue($done);

		$this->assertNull($this->sesh->get('foo'));
		$this->assertNull($this->sesh->foo);
	}

	public function testRemoveArray() {
		$bool = $this->sesh->set(array('foo' => 'oof', 'bar' => 'rab', 'baz' => 'zab'));

		$done = $this->sesh->remove(array('foo', 'bar'));

		// test the remove was okay
		$this->assertTrue($done);

		// make sure we no longer store these values
		$this->assertNull($this->sesh->get('foo'));
		$this->assertNull($this->sesh->foo);

		$this->assertNull($this->sesh->get('bar'));
		$this->assertNull($this->sesh->bar);

		// and make sure the one we didn't remove is still there
		$this->assertSame($this->sesh->baz, 'zab');

		// and ensure we get null if we unset something that doesn't exist
		$this->assertNull($this->sesh->remove('someInvalidVariable'));
	}

	public function testDestroy() {
		$this->sesh->destroy();

		$this->assertTrue('' === $this->sesh->id());
	}
}
