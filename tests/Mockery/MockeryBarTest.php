<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

class MockeryBarTest extends \PHPUnit\Framework\TestCase
{

	/** @var \Mockery\MockInterface|Foo */
	private $fooMock;

	protected function setUp(): void
	{
		$this->fooMock = \Mockery::mock(Foo::class);
	}

	public function testFooIsCalled(): void
	{
		$bar = new Bar($this->fooMock);

		$this->fooMock->expects()->doFoo()->andReturn('foo');
		self::assertSame('foo', $bar->doFoo());
	}

}
