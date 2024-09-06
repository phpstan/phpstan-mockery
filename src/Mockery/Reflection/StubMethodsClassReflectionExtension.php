<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Reflection;

use Mockery\Expectation;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ReflectionProvider;

class StubMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{

	private ReflectionProvider $reflectionProvider;

	private string $stubInterfaceName;

	public function __construct(ReflectionProvider $reflectionProvider, string $stubInterfaceName)
	{
		$this->reflectionProvider = $reflectionProvider;
		$this->stubInterfaceName = $stubInterfaceName;
	}

	public function hasMethod(ClassReflection $classReflection, string $methodName): bool
	{
		return $classReflection->getName() === $this->stubInterfaceName;
	}

	public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
	{
		if ($this->reflectionProvider->hasClass(Expectation::class)) {
			$classReflection = $this->reflectionProvider->getClass(Expectation::class);
		}
		return new StubMethodReflection($classReflection, $methodName);
	}

}
