<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use function usleep;

class MockeryTest extends TestCase
{

	/** @var MockInterface|Foo */
	private $fooMock;

	protected function setUp(): void
	{
		$this->fooMock = Mockery::mock(Foo::class);
	}

	public function testCreatedMock(): void
	{
		$fooMock = Mockery::mock(Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testExpectsMock(): void
	{
		$fooMock = Mockery::mock(Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->expects()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testAnotherMockTest(): void
	{
		$fooMock = Mockery::mock(Foo::class);
		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testAlternativeMockTest(): void
	{
		$fooMock = Mockery::mock(Foo::class);
		$fooMock->shouldReceive(['doFoo' => 'bar']);
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
		$interfaceMock = Mockery::mock(Baz::class, Buzz::class);
		$this->requireBaz($interfaceMock);
		$this->requireBuzz($interfaceMock);

		$interfaceMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $interfaceMock->doFoo());
	}

	public function testMockFooWithInterfaces(): void
	{
		$fooMock = Mockery::mock(Foo::class, Baz::class . ', ' . Buzz::class);
		$this->requireFoo($fooMock);
		$this->requireBaz($fooMock);
		$this->requireBuzz($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMockWithConstructorArgs(): void
	{
		$fooMock = Mockery::mock(Foo::class, [true]);
		$this->requireFoo($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMockWithInterfaceAndConstructorArgs(): void
	{
		$fooMock = Mockery::mock(Foo::class, Buzz::class, [true]);
		$this->requireFoo($fooMock);
		$this->requireBuzz($fooMock);

		$fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMockWithMethods(): void
	{
		$fooMock = Mockery::mock(Foo::class . '[doFoo]');
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testMockShouldAllowMockingProtectedMethods(): void
	{
		$fooMock = Mockery::mock(Foo::class)->shouldAllowMockingProtectedMethods();
		$this->requireFoo($fooMock);

		$fooMock->shouldReceive('doFoo')->once()->andReturn('bar');
		self::assertSame('bar', $fooMock->doFoo());
	}

	public function testMakePartial(): void
	{
		$fooMock = Mockery::mock(Foo::class)->makePartial();
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testNamedMock(): void
	{
		$fooMock = Mockery::namedMock('FooBar', Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	public function testMockFromClassString(): void
	{
		$this->requireMocksFromClassString(Foo::class);

		$fooMock = $this->mockFromClassString(Foo::class);
		$this->requireFoo($fooMock);

		$fooMock->allows()->doFoo()->andReturns('foo');
		self::assertSame('foo', $fooMock->doFoo());
	}

	/**
	 * @param class-string<Foo> $className
	 */
	private function requireMocksFromClassString(string $className): void
	{
		$this->requireFoo(Mockery::mock($className));
		$this->requireFoo(Mockery::spy($className));
		$this->requireFoo(Mockery::namedMock('FooFromClassString', $className));
	}

	/**
	 * @template T of object
	 * @param class-string<T> $className
	 * @return T&MockInterface
	 */
	private function mockFromClassString(string $className): MockInterface
	{
		return Mockery::mock($className);
	}

	public function testMockFromClassStringUnion(): void
	{
		self::assertSame('foo', $this->doFooOnMockFromConstantClassStringUnion(true));
		self::assertSame('foo', $this->doFooOnMockFromClassStringUnion(Bar::class));
	}

	private function doFooOnMockFromConstantClassStringUnion(bool $foo): ?string
	{
		$fooOrBarMock = Mockery::mock($foo ? Foo::class : Bar::class);
		$fooOrBarMock->shouldReceive('doFoo')->andReturn('foo');

		return $this->doFooOnFooOrBar($fooOrBarMock);
	}

	/**
	 * @param class-string<Foo>|class-string<Bar> $className
	 */
	private function doFooOnMockFromClassStringUnion(string $className): ?string
	{
		$fooOrBarMock = Mockery::mock($className);
		$fooOrBarMock->shouldReceive('doFoo')->andReturn('foo');

		return $this->doFooOnFooOrBar($fooOrBarMock);
	}

	private function requireFoo(Foo $foo): void
	{
		usleep(1);
	}

	/**
	 * @param Foo|Bar $fooOrBar
	 */
	private function doFooOnFooOrBar($fooOrBar): ?string
	{
		return $fooOrBar->doFoo();
	}

	private function requireBaz(Baz $baz): void
	{
		usleep(1);
	}

	private function requireBuzz(Buzz $buzz): void
	{
		usleep(1);
	}

}
