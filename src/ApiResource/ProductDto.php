<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Product;
use App\State\EntityClassDtoStateProcessor;
use App\State\EntityToDtoStateProvider;
use ApiPlatform\Doctrine\Orm\State\Options;


#[ApiResource(
    shortName: 'Product',
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Patch(),
        new Delete(),
    ],
    paginationItemsPerPage: 5,
    provider: EntityToDtoStateProvider::class,
    processor: EntityClassDtoStateProcessor::class,
    stateOptions: new Options(entityClass: Product::class),
)]
class ProductDto
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?string $id = null;

    #[ApiProperty(description: 'Nom du produit', example: 'Chaussures de course')]
    public ?string $name = null;

    #[ApiProperty(description: 'Description du produit', example: 'Chaussures légères et confortables')]
    public ?string $description = null;

    #[ApiProperty(description: 'Prix du produit', example: 59.99)]
    public ?float $price = null;

    #[ApiProperty(description: 'Date de création', readable: true, writable: false, example: '2024-01-01T12:00:00+00:00')]
    public ?\DateTimeInterface $createdAt = null;

    #[ApiProperty(description: 'Date de mise à jour', readable: true, writable: false, example: '2024-01-10T12:00:00+00:00')]
    public ?\DateTimeInterface $updatedAt = null;

    #[ApiProperty(description: 'Catégories associées au produit', readable: true, writable: false)]
    public array $categories = [];

    #[ApiProperty(description: 'Images associées au produit', readable: true, writable: false)]
    public array $images = [];
}