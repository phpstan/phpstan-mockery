<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Reflection;

use PHPStan\Mockery\Type\Allows;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class StubMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{

	public function hasMethod(ClassReflection $classReflection, string $methodName): bool
	{
		return $classReflection->getName() === Allows::class;
	}

	public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
	{
		return new StubMethodReflection($classReflection, $methodName);
	}

}
