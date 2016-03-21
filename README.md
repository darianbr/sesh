# Sesh
### Lightweight PHP session abstraction with Multi-session support.

Sesh is a lightweight and simple abstraction layer to PHP Sessions offering multi-session support. It simplifies the use, creation, and management of sessions as well as adding extra functionality to sessions.

## Current Build Status

[![Build Status](https://travis-ci.org/darianbr/sesh.svg)](https://travis-ci.org/darianbr/sesh)

## Usage Examples

### Creating a New Instance
```php
<?php

use Succinct\Sesh\Sesh;

// create our Sesh instance
$sesh = new Sesh();
```

You can optionally name the session which will be used as the key to store your data.
```php
<?php

use Succinct\Sesh\Sesh;

// create our Sesh instance
$sesh = new Sesh('my-unique-session-key');
```

### Multiple Sessions
Sesh supports multiple sessions allowing each instance to manage it's own data separately without affect other instances. To specify a unique session, give it a unique session key when creating your Sesh object.
```php
<?php

use Succinct\Sesh\Sesh;

// create our sesh instances
$seshOne = new Sesh('session-one');
$seshTwo = new Sesh('session-two');
```

If you destroy one session, the other one will still remain (unless you pass `true` to the `destroy()` method).
```php
<?php

$seshOne->destroy();

// $seshTwo will still have all it's data.
```

### Get and Set Session Values
```php
<?php

// set the value 'foo' to have the value 'bar'
$bool = $sesh->set('foo', 'bar');

// retrieve the value of the value 'foo'
$value = $sesh->get('foo');

// get the session id
$session_id = $sesh->id();
````

### Magic methods
Sesh supports magic methods. This will behave the same as above but will use magic getters and setters.

```php
<?php

// set the value 'foo' to have the value 'bar'
$sesh->foo = 'bar';

// retrieve the value of the value 'foo'
$value = $sesh->foo;
```

### Setting Multiple Value
Setting multiple values at once and retrieve them individually.
```php
<?php

$bool = $sesh->set(
	[
		'foo' => 'oof',
		'bar' => 'rab',
		'baz' => 'zab',
	]
);

// $value1 will be 'oof'
$value1 = $sesh->get('foo');

// $value1 will be 'rab'
$value2 = $sesh->get('bar');

// $value1 will be 'zab'
$value3 = $sesh->get('baz');
```

### Iterating Over Values
It is possible to loop over the Sesh object to accessing each value in turn.
```php
<?php

$this->sesh->foo = 'oof';
$this->sesh->bar = 'rab';
$this->sesh->baz = ['qux' => 'xuq', 'doom' => 'mood'];

foreach ($this->sesh as $key => $value) {
	echo "$key: $value" . PHP_EOL;
}
```

The above will echo out something like so (bear in mind echo'ing an array will just print Array).
```
foo: oof
bar: rab
baz: Array
```

### Isset and Removing Values
```php
<?php

$sesh->foo = 'bar';

// checking if a value exists
isset($sesh->foo);

// remove the value
unset($sesh->foo);

// alternate way to remove values
$sesh->remove('foo');
```

### Remove Multiple Values at Once
```php
<?php

$sesh->remove(array('foo', 'bar', 'baz'));
```

### Destroy a Session
This will destroy all session values managed by this instance of Sesh  (other session values are left alone unless you pass true to destroy).
```php
<?php

$sesh->destroy();

// other session data still available.
```

Destory ALL session data can be done by passing `true` to destroy. This will close the session all data, including data not associated with Sesh.
```php
<?php

$sesh->destroy(true);

// no session data exists and sessions are closed.
```
