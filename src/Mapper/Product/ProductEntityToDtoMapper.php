<?php

namespace App\Mapper\Product;

use App\ApiResource\ProductDto;
use App\Entity\Product;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: Product::class, to: ProductDto::class)]
class ProductEntityToDtoMapper implements MapperInterface
{
    public function __construct(private MicroMapperInterface $microMapper) {}

    public function load(object $from, string $toClass, array $context): object
    {
        $entity = $from;
        assert($entity instanceof Product);

        $dto = new ProductDto();
        $dto->id = $entity->getId();

        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;
        assert($entity instanceof Product);
        assert($dto instanceof ProductDto);

        $dto->name = $entity->getName();
        $dto->description = $entity->getDescription();
        $dto->price = $entity->getPrice();
        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();

        // Mappe les catégories
        $dto->categories = $entity->getCategories()->map(fn($category) => $category->getName())->toArray();

        // Mappe les images
        $dto->images = $entity->getImages()->map(fn($image) => $image->getUrl())->toArray();

        return $dto;
    }
}
