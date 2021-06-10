<?php declare(strict_types = 1);

namespace App;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

class DifferentNamespaceTest extends MockeryTestCase
{

	/** @var MockInterface&DifferentFoo */
	private $fooMock;

	protected function setUp(): void
	{
		parent::setUp();

		$this->fooMock = \Mockery::mock(DifferentFoo::class);
	}

	public function testWith(): void
	{
		$this->fooMock->expects('doFoo')
			->with(1)
			->andReturn(2);

		self::assertSame(2, $this->fooMock->doFoo(1));
	}

}
