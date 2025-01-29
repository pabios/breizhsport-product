<?php

namespace App\Domain\Listener;

use App\Domain\Service\JWTValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JWTAuthListener
{
    private JWTValidator $jwtValidator;

    public function __construct(JWTValidator $jwtValidator)
    {
        $this->jwtValidator = $jwtValidator;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Vérifier si on est sur une route nécessitant une authentification
        if (!str_starts_with($request->getPathInfo(), '/api/product')) {
            return;
        }

        $userData = $this->jwtValidator->validateToken($request);

        if (!$userData) {
            $event->setResponse(new JsonResponse(['error' => 'Unauthorized - Invalid JWT'], 401));
        }

        // Ajouter les données utilisateur au Request pour y accéder plus tard si besoin
        $request->attributes->set('user', $userData);
    }
}