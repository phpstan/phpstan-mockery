<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Reflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class StubMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{

	/** @var string */
	private $stubInterfaceName;

	public function __construct(string $stubInterfaceName)
	{
		$this->stubInterfaceName = $stubInterfaceName;
	}

	public function hasMethod(ClassReflection $classReflection, string $methodName): bool
	{
		return $classReflection->getName() === $this->stubInterfaceName;
	}

	public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
	{
		return new StubMethodReflection($classReflection, $methodName);
	}

}
