<?php declare(strict_types = 1);

namespace PHPStan\Mockery\Type;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use function array_filter;
use function array_values;
use function count;

class StubDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var string */
	private $stubInterfaceName;

	/** @var string */
	private $stubMethodName;

	public function __construct(string $stubInterfaceName, string $stubMethodName)
	{
		$this->stubInterfaceName = $stubInterfaceName;
		$this->stubMethodName = $stubMethodName;
	}

	public function getClass(): string
	{
		return 'Mockery\\MockInterface';
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === $this->stubMethodName;
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?Type
	{
		$calledOnType = $scope->getType($methodCall->var)->getObjectClassNames();
		$names = array_values(array_filter($calledOnType, static function (string $name) {
			return $name !== 'Mockery\\MockInterface';
		}));
		if (count($names) !== 1) {
			return null;
		}

		return new ObjectType($this->stubInterfaceName);
	}

}
