<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

class Bar
{

	/** @var Foo */
	private $foo;

	public function __construct(Foo $foo)
	{
		$this->foo = $foo;
	}

	public function doFoo(): ?string
	{
		return $this->foo->doFoo();
	}

}
