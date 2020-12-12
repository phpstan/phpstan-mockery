# PHPStan Mockery extension

[![Build](https://github.com/phpstan/phpstan-mockery/workflows/Build/badge.svg)](https://github.com/phpstan/phpstan-mockery/actions)
[![Latest Stable Version](https://poser.pugx.org/phpstan/phpstan-mockery/v/stable)](https://packagist.org/packages/phpstan/phpstan-mockery)
[![License](https://poser.pugx.org/phpstan/phpstan-mockery/license)](https://packagist.org/packages/phpstan/phpstan-mockery)

* [PHPStan](https://phpstan.org/)
* [Mockery](https://github.com/mockery/mockery)

This extension provides the following features:

* Interprets `Foo|\Mockery\MockInterface` in phpDoc so that it results in an intersection type instead of a union type.
* `Mockery::mock()` and `Mockery::spy()` return an intersection type (see the [detailed explanation of intersection types](https://phpstan.org/blog/union-types-vs-intersection-types)) so that the returned object can be used as both the mock object and the mocked class object.
* `shouldReceive()`, `allows()` and `expects()` methods can be called on the mock object and they work as expected.


## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev phpstan/phpstan-mockery
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
  <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include extension.neon in your project's PHPStan config:

```
includes:
    - vendor/phpstan/phpstan-mockery/extension.neon
```
</details>
