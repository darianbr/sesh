# Sesh

Sesh is an abstraction layer to PHP Sessions.

## Usage

### Basic example
```php
<?php

use Succinct\Sesh\Sesh;

// create our sesh instance
$this->sesh = new Sesh();

// set the variable 'foo' to have the value 'bar'
$bool = $this->sesh->set('foo', 'bar');

// retrieve the value of the variable 'foo'
$value = $this->sesh->get('foo');
````

### More Advanced exmaples

Coming soon

## Current build status

[![Build Status](https://travis-ci.org/darianbrown/sesh.png)](https://travis-ci.org/darianbrown/sesh)
