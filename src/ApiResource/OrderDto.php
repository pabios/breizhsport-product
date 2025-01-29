<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Action\OrderController;
use App\Entity\Order;
use App\Entity\Product;
use App\State\EntityClassDtoStateProcessor;
use App\State\EntityToDtoStateProvider;

#[ApiResource(
    shortName: 'Order',
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            uriTemplate: '/orders',
            controller: OrderController::class,
            read: false,
            name: 'create_order'
        ),
    ],
    paginationItemsPerPage: 5,
    provider: EntityToDtoStateProvider::class,
    processor: EntityClassDtoStateProcessor::class,
    stateOptions: new Options(entityClass: Order::class),

)]
class OrderDto
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?string $id = null;

    #[ApiProperty(description: "Email du client", example: "client@breizhsport.com")]
    public ?string $customerEmail = null;

    #[ApiProperty(description: "Date de commande", readable: true, writable: false, example: "2024-01-01T12:00:00+00:00")]
    public ?\DateTimeInterface $orderDate = null;

    #[ApiProperty(description: "Montant total de la commande", readable: true, writable: false, example: 199.99)]
    public ?float $totalAmount = null;

    #[ApiProperty(description: "Détails de la commande", readable: true, writable: true)]
    public array $orderDetails = [];
}