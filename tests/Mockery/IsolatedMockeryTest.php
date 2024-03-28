<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

use Mockery;
use PHPUnit\Framework\TestCase;
use function usleep;

class IsolatedMockeryTest extends TestCase
{

	public function testAliasMock(): void
	{
		$fooMock = Mockery::mock('alias:' . Foo2::class);
		$this->requireFoo2($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testOverloadMock(): void
	{
		$fooMock = Mockery::mock('overload:' . Foo3::class);
		$this->requireFoo3($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	private function requireFoo2(Foo2 $foo): void
	{
		usleep(1);
	}

	private function requireFoo3(Foo3 $foo): void
	{
		usleep(1);
	}

}
