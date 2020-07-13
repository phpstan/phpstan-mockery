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

	public function testMockInterface(): void
	{
		$interfaceMock = \Mockery::mock(Baz::class, Buzz::class);
		$this->requireBaz($interfaceMock);
		$this->requireBuzz($interfaceMock);

		$interfaceMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $interfaceMock->doFoo());
	}

	public function testMockFooWithInterfaces(): void
	{
		$fooMock = \Mockery::mock(Foo::class, Baz::class . ', ' . Buzz::class);
		$this->requireFoo($fooMock);
		$this->requireBaz($fooMock);
		$this->requireBuzz($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMockWithConstructorArgs(): void
	{
		$fooMock = \Mockery::mock(Foo::class, [true]);
		$this->requireFoo($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMockWithInterfaceAndConstructorArgs(): void
	{
		$fooMock = \Mockery::mock(Foo::class, Buzz::class, [true]);
		$this->requireFoo($fooMock);
		$this->requireBuzz($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMockWithMethods(): void
	{
		$fooMock = \Mockery::mock(Foo::class . '[doFoo]');
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testMockShouldAllowMockingProtectedMethods(): void
	{
		$fooMock = \Mockery::mock(Foo::class)->shouldAllowMockingProtectedMethods();
		$this->requireFoo($fooMock);

		$fooMock->shouldReceive('doFoo')->once()->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMakePartial(): void
	{
		$fooMock = \Mockery::mock(Foo::class)->makePartial();
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testNamedMock(): void
	{
		// $fooMock = \Mockery::namedMock('FooBar');
		$fooMock = \Mockery::namedMock('FooBar', Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	private function requireFoo(Foo $foo): void
	{
	}

	private function requireBaz(Baz $baz): void
	{
	}

	private function requireBuzz(Buzz $buzz): void
	{
	}

}
