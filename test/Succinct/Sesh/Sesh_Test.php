<?php

use Succinct\Sesh\Sesh;

class Sesh_Test extends PHPUnit_Framework_TestCase {

	protected $sesh = NULL;

	protected function setUp() {
		parent::setUp();

		$this->sesh = new Sesh();
	}

	/**
     * @runInSeparateProcess
     */
	// TODO: Fix - trying to register session here conflicts with already started sesion by setUp() method
	// public function testInit() {
	// 	$sesh = NULL;
	// 	$this->assertNull($sesh);
	// 	$sesh = new Sesh();
	// 	$this->assertEquals(get_resource_type($sesh), 'Sesh');
	// }

	/**
     * @runInSeparateProcess
     * @covers Sesh::set();
     * @covers Sesh::__set();
     */
	public function testSet() {
		$bool = $this->sesh->set('foo', 'bar');
		$this->assertTrue($bool);
	}

	/**
     * @runInSeparateProcess
     * @covers Sesh::get();
     * @covers Sesh::__get();
     */
	public function testGet() {
		$value = $this->sesh->get('foo');
		$this->assertNull($value);

		$value = $this->sesh->set('foo', 'bar');
		$value = $this->sesh->get('foo');
		$this->assertSame($value, 'bar');
	}
}
