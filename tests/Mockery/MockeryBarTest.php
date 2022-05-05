<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

class MockeryBarTest extends MockeryTestCase
{

	/** @var MockInterface|Foo */
	private $fooMock;

	protected function setUp(): void
	{
		parent::setUp();

		$this->fooMock = Mockery::mock(Foo::class);
	}

	public function testFooIsCalled(): void
	{
		$bar = new Bar($this->fooMock);

		$this->fooMock->expects()->doFoo()->andReturn('foo');
		self::assertSame('foo', $bar->doFoo());
	}

	public function testExpectationMethodsAreCalled(): void
	{
		$bar = new Bar($this->fooMock);

		$this->fooMock
			->shouldReceive('doFoo')
			->once()
			->times(1)
			->andReturn('foo');

		self::assertSame('foo', $bar->doFoo());
	}

	public function testShouldNotReceive(): void
	{
		$this->fooMock->shouldNotReceive('doFoo')->andReturn('bar');
	}

	public function testShouldReceive(): void
	{
		$this->fooMock->shouldReceive('doFoo')->andReturn('bar');
		self::assertSame('bar', $this->fooMock->doFoo());
	}

	public function testShouldNotHaveReceived(): void
	{
		$this->fooMock->shouldNotHaveReceived(null)->withArgs(['bar']);
	}

	public function testShouldHaveReceived(): void
	{
		$this->fooMock->allows('doFoo')->andReturn('bar');
		self::assertSame('bar', $this->fooMock->doFoo());
		$this->fooMock->shouldHaveReceived('doFoo')->once();
	}

}
