<?php

namespace App\Domain\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JWTValidator
{
    private string $secretKey = 'your-very-secure-secret-key'; // 🔐 Doit être identique à celle d'Auth !


    public function __construct() {
        $this->secretKey = $_ENV['JWT_SECRET'] ?? 'fallback-secret-key';
    }

    public function validateToken(Request $request): ?array
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return null; // Aucun token trouvé
        }

        $jwt = $matches[1];

        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));

            // Vérifie si les champs critiques existent
            if (!isset($decoded->iss) || !isset($decoded->sub) || !isset($decoded->email)) {
                return null;
            }

            // Vérifie si l'émetteur est bien BreizhSport
            if ($decoded->iss !== 'BreizhSport') {
                return null;
            }

            // Vérifie l'expiration
            if ($decoded->exp < time()) {
                return null; // Token expiré
            }

            return (array) $decoded; // Retourne les données du token

        } catch (\Exception $e) {
            return null; // Token invalide
        }
    }
}