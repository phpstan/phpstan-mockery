<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Type;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeWithClassName;

class ExpectsDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return 'Mockery\\MockInterface';
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'expects';
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$calledOnType = $scope->getType($methodCall->var);
		$defaultType = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();
		if (!$calledOnType instanceof IntersectionType || count($calledOnType->getTypes()) !== 2) {
			return $defaultType;
		}
		$mockedType = $calledOnType->getTypes()[1];
		if (!$mockedType instanceof TypeWithClassName) {
			return $defaultType;
		}

		return TypeCombinator::intersect(
			new ExpectsObjectType($mockedType->getClassName()),
			new ObjectType(Expects::class)
		);
	}

}
