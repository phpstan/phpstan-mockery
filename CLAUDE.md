# CLAUDE.md

This is the PHPStan extension for [Mockery](https://github.com/mockery/mockery), providing improved static analysis support for Mockery mock objects.

## Project Goal

This extension teaches PHPStan to understand Mockery's dynamic mock creation patterns so that static analysis works correctly with mocked objects. Without this extension, PHPStan cannot infer the correct types for mock objects created by Mockery.

### Key features provided:

- `Mockery::mock()` and `Mockery::spy()` return intersection types (e.g. `Foo&MockInterface`) so mocks can be used both as the mocked class and as a mock object
- `Mockery::namedMock()` returns the same intersection types (skipping the first name argument)
- `Foo|MockInterface` in phpDoc is interpreted as an intersection type instead of a union type (controlled by the `mockery.convertUnionToIntersectionType` parameter, enabled by default)
- `shouldReceive()`, `allows()`, `expects()` and related expectation methods are understood on mock objects
- Alias (`alias:`) and overload (`overload:`) mock prefixes are handled
- Partial mock syntax (`ClassName[methodName]`) is supported
- Multiple interfaces passed as comma-separated strings are resolved
- Constructor argument arrays passed to `mock()` are handled correctly

## Repository Structure

```
src/
  Mockery/
    PhpDoc/
      TypeNodeResolverExtension.php    # Converts Foo|MockInterface union to intersection in phpDoc
    Reflection/
      StubMethodReflection.php         # Method reflection for stub interfaces (Allows/Expects)
      StubMethodsClassReflectionExtension.php  # Makes any method callable on Allows/Expects interfaces
    Type/
      Allows.php                       # Marker interface for allows() return type
      Expects.php                      # Marker interface for expects() return type
      MockDynamicReturnTypeExtension.php       # Return types for Mockery::mock() and Mockery::spy()
      MockDynamicNamedMockReturnTypeExtension.php  # Return types for Mockery::namedMock()
      StubDynamicReturnTypeExtension.php       # Return types for allows() and expects() methods
stubs/
  MockInterface.stub                   # PHPStan stub for Mockery\MockInterface and LegacyMockInterface
tests/
  Mockery/
    data/                              # Test fixture classes (Foo, Bar, Baz, Buzz, etc.)
    MockeryTest.php                    # Tests for mock(), spy(), namedMock(), makePartial(), etc.
    MockeryBarTest.php                 # Tests for shouldReceive, expects, shouldHaveReceived, spies
    IsolatedMockeryTest.php            # Tests for alias: and overload: mock prefixes
    DifferentNamespaceTest.php         # Tests for mocks of classes in different namespaces
extension.neon                         # PHPStan extension configuration (services, parameters, stubs)
```

## PHP Version Support

This repository supports **PHP 7.4+**. The `composer.json` platform is set to PHP 7.4.6. Do not use language features unavailable in PHP 7.4 (e.g. enums, fibers, readonly properties, intersection types in PHP syntax, first-class callable syntax).

## Dependencies

- `phpstan/phpstan`: ^2.0
- `mockery/mockery`: ^1.6.11 (dev)
- `phpunit/phpunit`: ^9.6 (dev)

## Development Commands

All commands are defined in the `Makefile`:

```bash
# Run everything (lint, coding standard, tests, PHPStan analysis)
make check

# Run PHPUnit tests only
make tests

# Run PHP parallel lint on src/ and tests/
make lint

# Run coding standard checks (requires build-cs to be installed first)
make cs-install   # Clone and install phpstan/build-cs (only needed once)
make cs           # Check coding standard
make cs-fix       # Auto-fix coding standard violations

# Run PHPStan analysis at level 9
make phpstan
```

## Coding Standard

This project uses the [phpstan/build-cs](https://github.com/phpstan/build-cs) coding standard (branch `2.x`). The standard is applied via PHP_CodeSniffer with a configuration from the build-cs repository.

Key style rules from `.editorconfig`:
- Tabs for indentation in PHP, XML, and NEON files
- Spaces for indentation in YAML files
- UTF-8 charset, LF line endings, trailing whitespace trimmed, final newline inserted

## Testing

Tests use PHPUnit 9.6 and are located in `tests/Mockery/`. Test fixture classes live in `tests/Mockery/data/`. The bootstrap file is `tests/bootstrap.php` which loads the Composer autoloader.

Tests are integration tests that create actual Mockery mocks and verify PHPStan correctly understands the types. Some tests extend `PHPUnit\Framework\TestCase`, others extend `Mockery\Adapter\Phpunit\MockeryTestCase`.

## PHPStan Configuration

- `extension.neon` — the extension's service definitions and parameters, shipped to users
- `phpstan.neon` — project-level PHPStan configuration for self-analysis (level 9, includes strict-rules and phpunit extension, uses bleeding edge)
- `phpstan-baseline.neon` — baseline for known issues in the test suite

The extension registers services tagged with:
- `phpstan.broker.dynamicStaticMethodReturnTypeExtension` — for `Mockery::mock()`, `Mockery::spy()`, `Mockery::namedMock()`
- `phpstan.broker.dynamicMethodReturnTypeExtension` — for `allows()` and `expects()` on mock objects
- `phpstan.broker.methodsClassReflectionExtension` — for the `Allows` and `Expects` stub interfaces
- `phpstan.phpDoc.typeNodeResolverExtension` — for converting `Foo|MockInterface` unions to intersections

## CI

GitHub Actions workflow (`.github/workflows/build.yml`) runs on pushes to `2.0.x` and on pull requests. The matrix tests across PHP 7.4 through 8.5 with both lowest and highest dependency versions.

CI jobs:
1. **Lint** — `php-parallel-lint` across all PHP versions
2. **Coding Standard** — phpcs with build-cs on PHP 8.2
3. **Tests** — PHPUnit across all PHP versions with lowest/highest dependencies
4. **PHPStan** — self-analysis across all PHP versions with lowest/highest dependencies
