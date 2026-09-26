<?php declare(strict_types = 1);

namespace PHPStan\Mockery\PhpDoc;

use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\PhpDoc\TypeNodeResolverAwareExtension;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use function count;

class TypeNodeResolverExtension implements \PHPStan\PhpDoc\TypeNodeResolverExtension, TypeNodeResolverAwareExtension
{

	private TypeNodeResolver $typeNodeResolver;

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

		// TypeNodeResolver resolves the union again when this returns null, so resolving every
		// member here would resolve each nested union twice per level. Look at the direct
		// identifier members first: they cannot nest, and a mock class in a union is written as one.
		$hasMockClass = false;
		foreach ($typeNode->types as $innerTypeNode) {
			if (!$innerTypeNode instanceof IdentifierTypeNode) {
				continue;
			}

			$classNames = $this->typeNodeResolver->resolve($innerTypeNode, $nameScope)->getObjectClassNames();
			if (count($classNames) === 1 && $classNames[0] === 'Mockery\\MockInterface') {
				$hasMockClass = true;
				break;
			}
		}

		if (!$hasMockClass) {
			return null;
		}

		$types = $this->typeNodeResolver->resolveMultiple($typeNode->types, $nameScope);
		foreach ($types as $type) {
			$classNames = $type->getObjectClassNames();
			if (count($classNames) !== 1) {
				continue;
			}

			if (
				count($types) === 2
				&& $classNames[0] === 'Mockery\\MockInterface'
			) {
				return TypeCombinator::intersect(...$types);
			}
		}

		return null;
	}

}
