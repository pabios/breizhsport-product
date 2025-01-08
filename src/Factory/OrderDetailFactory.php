<?php

namespace App\Factory;

use App\Entity\OrderDetail;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<OrderDetail>
 */
final class OrderDetailFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return OrderDetail::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
//        return [
//            'quantity' => self::faker()->numberBetween(1, 10), // Quantité entre 1 et 10
//            'unitPrice' => self::faker()->randomFloat(2, 10, 500), // Prix unitaire entre 10 et 500
//            'product' => ProductFactory::random(), // Produit aléatoire
//            'order' => OrderFactory::random(), // Commande aléatoire
//        ];

        return [
            'quantity' => self::faker()->numberBetween(1, 10), // Quantité entre 1 et 10
            'unitPrice' => self::faker()->randomFloat(2, 10, 500), // Prix unitaire entre 10 et 500
            'product' => ProductFactory::randomOrCreate(), // Produit aléatoire ou crée-en un
            'order' => OrderFactory::randomOrCreate(), // Commande aléatoire ou crée-en une
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderDetail $orderDetail): void {})
        ;
    }
}
