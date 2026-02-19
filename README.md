# PHPStan Mockery extension

[![Build](https://github.com/phpstan/phpstan-mockery/workflows/Build/badge.svg)](https://github.com/phpstan/phpstan-mockery/actions)
[![Latest Stable Version](https://poser.pugx.org/phpstan/phpstan-mockery/v/stable)](https://packagist.org/packages/phpstan/phpstan-mockery)
[![License](https://poser.pugx.org/phpstan/phpstan-mockery/license)](https://packagist.org/packages/phpstan/phpstan-mockery)

* [PHPStan](https://phpstan.org/)
* [Mockery](https://github.com/mockery/mockery)

This extension provides the following features:

* `Mockery::mock()`, `Mockery::spy()`, and `Mockery::namedMock()` return an intersection type (e.g. `Foo&MockInterface`) so that the mock can be used as both the mocked class and a mock object. See the [detailed explanation of intersection types](https://phpstan.org/blog/union-types-vs-intersection-types).
* Interprets `Foo|\Mockery\MockInterface` in phpDoc so that it results in an intersection type instead of a union type. This can be disabled by setting the `mockery.convertUnionToIntersectionType` parameter to `false`.
* `shouldReceive()`, `shouldNotReceive()`, `shouldHaveReceived()`, `shouldNotHaveReceived()`, `allows()`, and `expects()` methods are understood on mock objects.
* `makePartial()` and `shouldAllowMockingProtectedMethods()` return `static`, preserving the intersection type for chained calls.
* Alias (`alias:`) and overload (`overload:`) mock prefixes are handled.
* Partial mock syntax (`ClassName[methodName]`) is supported.
* Multiple interfaces passed as comma-separated strings or as separate arguments are resolved.
* Constructor argument arrays passed to `mock()` are handled correctly.


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
