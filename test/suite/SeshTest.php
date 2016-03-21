<?php

namespace Succinct\Sesh;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class SeshTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->sesh = new Sesh;
    }

    public function testId()
    {
        $this->assertTrue('' !== $this->sesh->id());
    }

    public function testSet()
    {
        $this->assertTrue($this->sesh->set('foo', 'bar'));
    }

    public function test_Set()
    {
        $this->sesh->foo = 'bar';
        $this->assertSame($this->sesh->foo, 'bar');
    }

    public function testSetArray()
    {
        $this->assertTrue(
            $this->sesh->set(['foo' => 'oof', 'bar' => 'rab', 'baz' => 'zab'])
        );
    }

    public function testGet()
    {
        $this->assertNull($this->sesh->get('foo'));

        $this->sesh->set('foo', 'bar');
        $this->assertSame($this->sesh->get('foo'), 'bar');

        // make sure non-existant variable are returned as null
        $this->assertNull($this->sesh->get('someInvalidVariable'));
    }

    public function test_Get()
    {
        $this->assertNull($this->sesh->foo);

        $this->sesh->foo = 'bar';
        $this->assertSame($this->sesh->foo, 'bar');

        // make sure non-existant variable are returned as null
        $this->assertNull($this->sesh->someInvalidVariable);
    }

    public function test_GetWithInvalidParameter()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            'Get must be a valid string.'
        );

        $foo = '';

        $bar = $this->sesh->{$foo};
    }

    /**
     * There is no get as array feature, but we want to test that the set with array method
     * 	is doing its jobs correctly
     */
    public function testGetArray()
    {
        $this->assertNull($this->sesh->get('foo'));

        // check it set correctly
        $this->assertTrue(
            $this->sesh->set(['foo' => 'oof', 'bar' => 'rab', 'baz' => 'zab'])
        );

        $this->assertSame($this->sesh->get('foo'), 'oof');
        $this->assertSame($this->sesh->get('bar'), 'rab');
        $this->assertSame($this->sesh->get('baz'), 'zab');
    }

    public function test_Isset()
    {
        $this->sesh->foo = 'bar';

        $this->assertTrue(isset($this->sesh->foo));
        $this->assertFalse(empty($this->sesh->foo));

        $this->assertFalse(isset($this->sesh->someInvalidVariable));
        $this->assertTrue(empty($this->sesh->someInvalidVariable));
    }

    public function test_IssetWithInvalidParameter()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            'Isset requires a valid string.'
        );

        $foo = '';
        $this->sesh->{$foo} = 'bar';

        isset($this->sesh->{$foo});
    }

    public function testIterator()
    {
        $this->sesh->foo = 'oof';
        $this->sesh->bar = 'rab';
        $this->sesh->baz = ['qux' => 'xuq', 'doom' => 'mood'];

        $result = [];
        foreach ($this->sesh as $key => $value) {
            $result[$key] = $value;
        }

        $this->assertSame(
            [
                'foo' => 'oof',
                'bar' => 'rab',
                'baz' => [
                    'qux' => 'xuq',
                    'doom' => 'mood',
                ],
            ],
            $result
        );
    }

    public function test_Unset()
    {
        $this->sesh->foo = 'bar';

        $this->assertSame('bar', $this->sesh->foo);

        unset($this->sesh->foo);

        $this->assertFalse(isset($this->sesh->foo));
        $this->assertTrue(empty($this->sesh->foo));
    }

    public function testRemove()
    {
        $this->sesh->foo = 'bar';

        $this->assertTrue($this->sesh->remove('foo'));

        $this->assertNull($this->sesh->get('foo'));
        $this->assertNull($this->sesh->foo);
    }

    public function testRemoveArray()
    {
        // check it set correctly
        $this->assertTrue(
            $this->sesh->set(['foo' => 'oof', 'bar' => 'rab', 'baz' => 'zab'])
        );

        // test the remove was okay
        $this->assertTrue($this->sesh->remove(['foo', 'bar']));

        // make sure we no longer store these values
        $this->assertNull($this->sesh->get('foo'));
        $this->assertNull($this->sesh->foo);

        $this->assertNull($this->sesh->get('bar'));
        $this->assertNull($this->sesh->bar);

        // and make sure the one we didn't remove is still there
        $this->assertSame($this->sesh->baz, 'zab');

        // and ensure we get true if we unset something that doesn't exist
        $this->assertTrue($this->sesh->remove('someInvalidVariable'));
    }

    public function testDestroy()
    {
        $this->sesh->foo = 'oof';
        $this->assertSame('oof', $this->sesh->foo);

        $this->sesh->destroy();

        $this->assertNull($this->sesh->foo);
    }

    public function testGlobalDestroy()
    {
        $this->sesh->foo = 'oof';
        $this->assertSame('oof', $this->sesh->foo);

        $this->sesh->destroy(true);

        $this->assertNull($this->sesh->foo);
        $this->assertSame('', $this->sesh->id());
    }

    public function testMultipleSessions()
    {
        $one = new Sesh('one');
        $two = new Sesh('two');

        $one->foo = 'bar';
        $two->baz = 'qux';

        $this->assertSame('bar', $one->foo);
        $this->assertSame('qux', $two->baz);

        $this->assertNull($one->baz);
        $this->assertNull($two->foo);

        $one->destroy();

        // make sure only $one was destroyed
        $this->assertNull($one->baz);
        $this->assertSame('qux', $two->baz);
    }
}
