<?php

use App\Controller\Action\OrderController;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

//vendor/bin/pest --filter OrderTest


it('crée une commande avec succès', function () {
    // 🛠 Mock des dépendances
    $entityManager = Mockery::mock(EntityManagerInterface::class);
    $productRepository = Mockery::mock(ProductRepository::class);

    // ✅ Permettre les appels à persist() et flush()
    $entityManager->shouldReceive('persist')->andReturnNull();
    $entityManager->shouldReceive('flush')->andReturnNull();

    // 🏗 Création d'un faux produit
    $product = new Product();
    $product->setId('1efce849-2822-6b62-b192-dbb749144040');
    $product->setPrice(50.99);

    // 🛠 Simule le retour du repository
    $productRepository->shouldReceive('find')
        ->with('1efce849-2822-6b62-b192-dbb749144040')
        ->andReturn($product);

    // 🏗 Création d'une requête JSON simulée
    $data = [
        "customerEmail" => "test@client.com",
        "orderDetails" => [
            ["productId" => "1efce849-2822-6b62-b192-dbb749144040", "quantity" => 2],
            ["productId" => "1efce849-2822-6b62-b192-dbb749144040", "quantity" => 1]
        ]
    ];
    $request = new Request([], [], [], [], [], [], json_encode($data));

    // 🎯 Instancier le contrôleur
    $controller = new OrderController();

    // 🚀 Exécuter la méthode `createOrder`
    $response = $controller->createOrder($request, $entityManager, $productRepository);

    // ✅ Vérifications
    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(201);

    $jsonResponse = json_decode($response->getContent(), true);
    expect($jsonResponse['message'])->toBe('Commande created avec success');
    expect($jsonResponse)->toHaveKeys(['orderId', 'totalAmount']);
    expect($jsonResponse['totalAmount'])->toBe(50.99 * 3); // 3 produits à 50.99€
});
