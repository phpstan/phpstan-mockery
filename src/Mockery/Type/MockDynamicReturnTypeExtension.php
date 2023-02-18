<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Type;

use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use function count;
use function explode;
use function in_array;
use function str_replace;
use function strpos;
use function substr;

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
			$constantStrings = $classType->getConstantStrings();
			if (count($constantStrings) !== 1) {
				continue;
			}

			$value = $constantStrings[0]->getValue();
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
