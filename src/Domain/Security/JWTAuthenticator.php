<?php

namespace App\Domain\Security;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class JWTAuthenticator extends AbstractAuthenticator
{
    private string $secretKey;

    public function __construct() {
        $this->secretKey = $_ENV['JWT_SECRET'] ?? 'fallback-secret-key';
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new AuthenticationException('Token manquant ou mal formaté');
        }

        $jwt = substr($authHeader, 7); // Supprime "Bearer " pour récupérer le token

        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
        } catch (\Exception $e) {
            throw new AuthenticationException('Token invalide ou expiré');
        }

        // 🔹 Correction et validation des rôles
        $roles = [];
        if (isset($decoded->roles)) {
            if (is_string($decoded->roles)) {
                $roles = explode(',', $decoded->roles); // Convertit une chaîne de rôles en tableau
            } elseif (is_array($decoded->roles)) {
                $roles = array_filter($decoded->roles, 'is_string'); // Filtre les rôles invalides
            }
        }

        // 🔥 Ajoute un rôle minimal par défaut si vide
        if (empty($roles)) {
            $roles = ['ROLE_USER'];
        }

        return new Passport(
            new UserBadge($decoded->email, function ($email) use ($decoded, $roles) {
                return new class ($decoded->sub, $decoded->email, $roles) implements UserInterface {
                    private string $id;
                    private string $email;
                    private array $roles;

                    public function __construct(string $id, string $email, array $roles)
                    {
                        $this->id = $id;
                        $this->email = $email;
                        $this->roles = $roles;
                    }

                    public function getUserIdentifier(): string
                    {
                        return $this->email;
                    }

                    public function getId(): string
                    {
                        return $this->id;
                    }

                    public function getRoles(): array
                    {
                        return $this->roles;
                    }

                    public function eraseCredentials(): void {}
                };
            }),
            new CustomCredentials(fn() => true, $decoded)
        );
    }


    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        return null; // Laisse Symfony gérer la suite (pas de redirection en API)
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Échec d\'authentification',
            'error' => $exception->getMessage()
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}