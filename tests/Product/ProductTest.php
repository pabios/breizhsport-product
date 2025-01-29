<?php

// commande
// vendor/bin/pest --testdox


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//use App\Kernel;

uses(WebTestCase::class);

beforeEach(function () {
    $this->client = static::createClient();
});

/** Déclare le Kernel de Symfony */
function getKernelClass(): string
{
    return Kernel::class;
}

/** 🛠 Teste la récupération de la liste des produits */
it('retrieves a list of products', function () {
    $this->client->request('GET', '/api/products');
    expect($this->client->getResponse()->getStatusCode())->toBe(200)
        ->and($this->client->getResponse()->headers->get('Content-Type'))->toBe('application/ld+json; charset=utf-8')
        ->and($this->client->getResponse()->getContent())->toBeJson();
});
