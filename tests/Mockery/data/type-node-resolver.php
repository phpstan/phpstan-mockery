<?php declare(strict_types = 1);

namespace MockeryTypeNode;

use Mockery\MockInterface;
use Mockery\MockInterface as Mock;
use function PHPStan\Testing\assertType;

class Foo
{

}

/**
 * @phpstan-type FooMock MockInterface
 */
class Test
{

	/**
	 * @param MockInterface|Foo $a
	 * @param Foo|MockInterface $b
	 * @param \Mockery\MockInterface|Foo $c
	 * @param Mock|Foo $d
	 * @param FooMock|Foo $e
	 * @param MockInterface|Foo|null $f
	 * @param (MockInterface|Foo)|null $g
	 * @param Foo|int $h
	 */
	public function mockUnions($a, $b, $c, $d, $e, $f, $g, $h): void
	{
		assertType('Mockery\\MockInterface&MockeryTypeNode\\Foo', $a);
		assertType('Mockery\\MockInterface&MockeryTypeNode\\Foo', $b);
		assertType('Mockery\\MockInterface&MockeryTypeNode\\Foo', $c);
		assertType('Mockery\\MockInterface&MockeryTypeNode\\Foo', $d);
		assertType('Mockery\\MockInterface&MockeryTypeNode\\Foo', $e);
		assertType('Mockery\\MockInterface|MockeryTypeNode\\Foo|null', $f);
		assertType('(Mockery\\MockInterface&MockeryTypeNode\\Foo)|null', $g);
		assertType('int|MockeryTypeNode\\Foo', $h);
	}

	/**
	 * Each nesting level used to resolve its inner union twice, so this took 2^30 steps.
	 *
	 * @param (((((((((((((((((((((((((((((int|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string)|string $a
	 * @param (((((((((((((((((((((((((((((MockInterface|Foo)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null)|null $b
	 */
	public function deeplyNestedUnions($a, $b): void
	{
		assertType('int|string', $a);
		assertType('(Mockery\\MockInterface&MockeryTypeNode\\Foo)|null', $b);
	}

}
