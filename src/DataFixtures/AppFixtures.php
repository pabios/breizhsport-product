<?php
//php bin/console doctrine:fixtures:load

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\ImageFactory;
use App\Factory\InventoryFactory;
use App\Factory\OrderDetailFactory;
use App\Factory\OrderFactory;
use App\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // Crée 5 catégories
        $categories = CategoryFactory::createMany(5);

        // Crée 20 produits associés à des catégories
        ProductFactory::createMany(20, function () use ($categories) {
            return [
                'categories' => CategoryFactory::randomSet(2), // Associe 2 catégories aléatoires
            ];
        });

        // Crée 50 stocks associés à des produits
        InventoryFactory::createMany(50);

        // Crée 100 images associées à des produits
        ImageFactory::createMany(100, function () {
            return [
                'product' => ProductFactory::random(), // Associe chaque image à un produit aléatoire
            ];
        });

        // Crée 10 commandes
        OrderFactory::createMany(10);

        // Associe des lignes de commande à des commandes existantes
        OrderDetailFactory::createMany(50, function () {
            return [
                'order' => OrderFactory::random(),
                'product' => ProductFactory::random(),
            ];
        });

        $manager->flush();
    }
}
