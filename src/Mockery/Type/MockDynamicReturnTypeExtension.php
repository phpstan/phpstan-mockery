<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Type;

use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

class MockDynamicReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return 'Mockery';
	}

	public function isStaticMethodSupported(MethodReflection $methodReflection): bool
	{
		return in_array($methodReflection->getName(), [
			'mock',
			'spy',
		], true);
	}

	public function getTypeFromStaticMethodCall(
		MethodReflection $methodReflection,
		StaticCall $methodCall,
		Scope $scope
	): Type
	{
		$defaultReturnType = new ObjectType('Mockery\\MockInterface');
		if (count($methodCall->getArgs()) === 0) {
			return $defaultReturnType;
		}

		$types = [$defaultReturnType];
		foreach ($methodCall->getArgs() as $arg) {
			$classType = $scope->getType($arg->value);
			if (!$classType instanceof ConstantStringType) {
				continue;
			}

			$value = $classType->getValue();
			if (substr($value, 0, 6) === 'alias:') {
				$value = substr($value, 6);
			}
			if (substr($value, 0, 9) === 'overload:') {
				$value = substr($value, 9);
			}
			if (substr($value, -1) === ']' && strpos($value, '[') !== false) {
				$value = substr($value, 0, strpos($value, '['));
			}

			if (strpos($value, ',') !== false) {
				$interfaceNames = explode(',', str_replace(' ', '', $value));
			} else {
				$interfaceNames = [$value];
			}

			foreach ($interfaceNames as $name) {
				$types[] = new ObjectType($name);
			}
		}

		return TypeCombinator::intersect(...$types);
	}

}
