<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

class Foo implements Baz
{

	/** @var bool */
	private $optional;

	public function __construct(bool $optional = true)
	{
		$this->optional = $optional;
	}

	public function doFoo(): ?string
	{
		if ($this->optional) {
			return null;
		}

		return 'foo';
	}

}
