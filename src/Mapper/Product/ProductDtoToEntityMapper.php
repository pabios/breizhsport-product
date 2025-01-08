<?php

namespace App\Mapper\Product;

use App\ApiResource\ProductDto;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: ProductDto::class, to: Product::class)]
class ProductDtoToEntityMapper implements MapperInterface
{
    public function __construct(
        private MicroMapperInterface $microMapper,
        private ProductRepository $productRepository
    ) {}

    public function load(object $from, string $toClass, array $context): object
    {
        $dto = $from;
        assert($dto instanceof ProductDto);

        return $dto->id ? $this->productRepository->find($dto->id) : new Product();
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        assert($dto instanceof ProductDto);
        assert($entity instanceof Product);

        $entity->setName($dto->name);
        $entity->setDescription($dto->description);
        $entity->setPrice($dto->price);

        return $entity;
    }
}
