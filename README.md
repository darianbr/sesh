# Sesh

Sesh is a lightweight and simple abstraction layer to PHP Sessions. It simplifies the use of sessions and adds extra functionality to sessions.

## Usage Examples

### Creating a new instance
```php
<?php

use Succinct\Sesh\Sesh;

// create our sesh instance
$this->sesh = new Sesh();

```

### Get and Set session variables
```php
// set the variable 'foo' to have the value 'bar'
$bool = $this->sesh->set('foo', 'bar');

// retrieve the value of the variable 'foo'
$value = $this->sesh->get('foo');

// get the session id
$session_id = $this->sesh->id();
````

### Same as above but using magic getters and setters
```php
// set the variable 'foo' to have the value 'bar'
$this->sesh->foo = 'bar';

// retrieve the value of the variable 'foo'
$vale = $this->sesh->foo;
```

### Setting multiple values at once and retrieve them individually
```php
$bool = $this->sesh->set(
	array(
		'foo' => 'oof'
		, 'bar' => 'rab'
		, 'baz' => 'zab'
	)
);

// $value1 will be 'oof'
$value1 = $this->sesh->get('foo');

// $value1 will be 'rab'
$value2 = $this->sesh->get('bar');

// $value1 will be 'zab'
$value3 = $this->sesh->get('baz');
```

### Isset and Removing variables
```php
$this->sesh->foo = 'bar';

// checking if a variable exists
isset($this->sesh->foo);

// remove the value
unset($this->sesh->foo);

// alternate way to remove values
$this->sesh->remove(foo);
```

### Remove Multiple variables at once
```php
$this->sesh->remove(array('foo', 'bar', 'baz'));
```

### Destroy the session
This will close the session and remove all session variables managed by this class (other session variables are left alone)
```php
$this->sesh->destroy();
```

## Current Build Status

[![Build Status](https://travis-ci.org/darianbrown/sesh.png)](https://travis-ci.org/darianbrown/sesh)
