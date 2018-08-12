<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Type;

use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
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
		$defaultReturnType = ParametersAcceptorSelector::selectSingle(
			$methodReflection->getVariants()
		)->getReturnType();
		if (count($methodCall->args) === 0) {
			return $defaultReturnType;
		}

		$classType = $scope->getType($methodCall->args[0]->value);
		if (!$classType instanceof ConstantStringType) {
			return $defaultReturnType;
		}

		return TypeCombinator::intersect(
			$defaultReturnType,
			new ObjectType($classType->getValue())
		);
	}

}
