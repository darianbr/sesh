# Sesh

Sesh is a lightweight and simple abstraction layer to PHP Sessions. It simplifies the use of sessions and adds extra functionality to sessions.

## Current Build Status

[![Build Status](https://travis-ci.org/darianbrown/sesh.png)](https://travis-ci.org/darianbrown/sesh)

## Usage Examples

### Creating a new instance
```php
<?php

use Succinct\Sesh\Sesh;

// create our sesh instance
$sesh = new Sesh();

```

### Get and Set session variables
```php
// set the variable 'foo' to have the value 'bar'
$bool = $sesh->set('foo', 'bar');

// retrieve the value of the variable 'foo'
$value = $sesh->get('foo');

// get the session id
$session_id = $sesh->id();
````

### Same as above but using magic getters and setters
```php
// set the variable 'foo' to have the value 'bar'
$sesh->foo = 'bar';

// retrieve the value of the variable 'foo'
$value = $sesh->foo;
```

### Setting multiple values at once and retrieve them individually
```php
$bool = $sesh->set(
	array(
		'foo' => 'oof'
		, 'bar' => 'rab'
		, 'baz' => 'zab'
	)
);

// $value1 will be 'oof'
$value1 = $sesh->get('foo');

// $value1 will be 'rab'
$value2 = $sesh->get('bar');

// $value1 will be 'zab'
$value3 = $sesh->get('baz');
```

### Isset and Removing variables
```php
$sesh->foo = 'bar';

// checking if a variable exists
isset($sesh->foo);

// remove the value
unset($sesh->foo);

// alternate way to remove values
$sesh->remove('foo');
```

### Remove Multiple variables at once
```php
$sesh->remove(array('foo', 'bar', 'baz'));
```

### Destroy the session
This will close the session and remove all session variables managed by this class (other session variables are left alone)
```php
$sesh->destroy();
```
