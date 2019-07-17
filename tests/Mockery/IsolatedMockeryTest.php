<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

/**
 * @runTestsInSeparateProcesses
 */
class IsolatedMockeryTest extends \PHPUnit\Framework\TestCase
{

	public function testAliasMock(): void
	{
		$fooMock = \Mockery::mock('alias:' . Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testOverloadMock(): void
	{
		$fooMock = \Mockery::mock('overload:' . Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	private function requireFoo(Foo $foo): void
	{
	}

}
