<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return new Response(
            '<html><body><h1>Bienvenue sur le microservice Breizhsport {Product}</h1></body></html>'
        );
    }

    #[Route('/api/product', name: 'api_product')]
    public function getProduct(): JsonResponse
    {
        return new JsonResponse(['message' => 'Access granted to Product API']);
    }

    #[Route('/api/debug-token', name: 'debug_token', methods: ['GET'])]
    public function debugToken(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $token = $tokenStorage->getToken();

        if (!$token) {
            return $this->json(['error' => 'No token found'], 401);
        }

        return $this->json([
            'roles' => $token->getRoleNames(),
            'username' => $token->getUserIdentifier(),
            'raw_token' => $token->getCredentials(),
        ]);
    }
}
