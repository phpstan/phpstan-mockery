<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Reflection;

use PHPStan\Mockery\Type\Expects;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class ExpectsMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{

	public function hasMethod(ClassReflection $classReflection, string $methodName): bool
	{
		return $classReflection->getName() === Expects::class;
	}

	public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
	{
		return new AllowsMethodReflection($classReflection, $methodName);
	}

}
