# PHPStan Mockery extension

[![Build Status](https://travis-ci.org/phpstan/phpstan-mockery.svg)](https://travis-ci.org/phpstan/phpstan-mockery)
[![Latest Stable Version](https://poser.pugx.org/phpstan/phpstan-mockery/v/stable)](https://packagist.org/packages/phpstan/phpstan-mockery)
[![License](https://poser.pugx.org/phpstan/phpstan-mockery/license)](https://packagist.org/packages/phpstan/phpstan-mockery)

* [PHPStan](https://github.com/phpstan/phpstan)
* [Mockery](https://github.com/mockery/mockery)

This extension provides the following features:

* Interprets `Foo|\Mockery\MockInterface` in phpDoc so that it results in an intersection type instead of a union type.
* `Mockery::mock()` and `Mockery::spy()` return an intersection type (see the [detailed explanation of intersection types](https://medium.com/@ondrejmirtes/union-types-vs-intersection-types-fd44a8eacbb)) so that the returned object can be used as both the mock object and the mocked class object.
* `shouldReceive()`, `allows()` and `expects()` methods can be called on the mock object and they work as expected.

## Usage

To use this extension, require it in [Composer](https://getcomposer.org/):

```bash
composer require --dev phpstan/phpstan-mockery
```

And include extension.neon in your project's PHPStan config:

```
includes:
	- vendor/phpstan/phpstan-mockery/extension.neon
```
