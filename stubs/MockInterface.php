<?php

namespace Mockery;

interface MockInterface
{

	/**
	 * @param string[] ...$methodNames
	 * @return Expectation
	 */
	public function shouldReceive(...$methodNames);

	/**
	 * @return static
	 */
	public function makePartial();

}

interface LegacyMockInterface
{

	/**
	 * @param string[] ...$methodNames
	 * @return Expectation
	 */
	public function shouldReceive(...$methodNames);

	/**
	 * @return static
	 */
	public function makePartial();

}
