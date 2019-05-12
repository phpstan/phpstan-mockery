<?php declare(strict_types = 1);

namespace PHPStan\Mockery;

class Foo
{

	public function doFoo(): ?string
	{
		if (rand(0, 1) === 0) {
			return null;
		}

		return 'foo';
	}

}
