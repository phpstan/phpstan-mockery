<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

class MockeryBarTest extends MockeryTestCase
{

	/** @var MockInterface&Foo */
	private $fooMock;

	/** @var MockInterface&Foo */
	private $fooSpy;

	protected function setUp(): void
	{
		parent::setUp();

		$this->fooMock = Mockery::mock(Foo::class);
		$this->fooSpy = Mockery::spy(Foo::class);
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
		$this->fooSpy->shouldNotHaveReceived(null)->withArgs(['bar']);
		$this->fooSpy->doBar('ccc');
		$this->fooSpy->shouldNotHaveReceived('doBar', ['ddd']);
		$this->fooSpy->shouldNotHaveReceived('doBar', static function (string $arg): bool {
			return $arg !== 'ccc';
		});
	}

	public function testShouldHaveReceived(): void
	{
		$this->fooSpy->allows('doFoo')->andReturn('bar');
		self::assertSame('bar', $this->fooSpy->doFoo());
		$this->fooSpy->shouldHaveReceived('doFoo')->once();
		$this->fooSpy->doBar('ccc');
		$this->fooSpy->shouldHaveReceived('doBar', ['ccc']);
		$this->fooSpy->shouldHaveReceived('doBar', static function (string $arg): bool {
			return $arg === 'ccc';
		});
	}

}
