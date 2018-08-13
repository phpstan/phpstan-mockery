<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

class MockeryTest extends \PHPUnit\Framework\TestCase
{

	/** @var \Mockery\MockInterface|Foo */
	private $fooMock;

	protected function setUp(): void
	{
		$this->fooMock = \Mockery::mock(Foo::class);
	}

	public function testCreatedMock(): void
	{
		$fooMock = \Mockery::mock(Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testExpectsMock(): void
	{
		$fooMock = \Mockery::mock(Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->expects()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testAnotherMockTest(): void
	{
		$fooMock = \Mockery::mock(Foo::class);
		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMockFromProperty(): void
	{
		$this->requireFoo($this->fooMock);

		$this->fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $this->fooMock->doFoo());
	}

	private function requireFoo(Foo $foo): void
	{
	}

}
