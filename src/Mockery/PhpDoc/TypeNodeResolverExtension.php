<?php declare(strict_types = 1);

namespace PHPStan\Mockery\PhpDoc;

use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;

class TypeNodeResolverExtension implements \PHPStan\PhpDoc\TypeNodeResolverExtension, \PHPStan\PhpDoc\TypeNodeResolverAwareExtension
{

	/** @var TypeNodeResolver */
	private $typeNodeResolver;

	public function setTypeNodeResolver(TypeNodeResolver $typeNodeResolver): void
	{
		$this->typeNodeResolver = $typeNodeResolver;
	}

	public function getCacheKey(): string
	{
		return 'mockery-v1';
	}

	public function resolve(TypeNode $typeNode, NameScope $nameScope): ?Type
	{
		if (!$typeNode instanceof UnionTypeNode) {
			return null;
		}

		$types = $this->typeNodeResolver->resolveMultiple($typeNode->types, $nameScope);
		foreach ($types as $type) {
			if (!$type instanceof TypeWithClassName) {
				continue;
			}

			if (
				count($types) === 2
				&& $type->getClassName() === 'Mockery\\MockInterface'
			) {
				return \PHPStan\Type\TypeCombinator::intersect(...$types);
			}
		}

		return null;
	}

}
