<?php

namespace App\Service;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;


class JwtService
{

    private Configuration $jwtConfig;

    public function __construct()
    {
        // Clé publique (remplacez par votre clé)
        $publicKey = <<<KEY
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAw6onMLRyceZmel4mSflT
Adit2+pC9IlQjmD8y9H3PEBV1zBtjhdgSrJ1RzNWvHQezX8O93BY2gB72puOE9pb
s4P1mls+/9url1xyLinQNhXvi2wCQjmTal6YTb7AVqx/UNBBZNdnj+CvXbfGp+Vd
FzxWkXfpUYQQqJkCG+501eNPg2yJHoegCQU/Gjm4ogt3J55L6Pdhg+2wMugSxyyx
Irii/4w/BK55gAjSsL+VA/Ej0NF35MFA6fTU+b9vSe8iNmMDgBQPFS+Gcxzkgwp9
9EpS7wedC3PKKhIEgUlFKoPRjXn0eYbJsJULHnQxYOBPQC6KtfBNM5HXzNwqaS5b
dwIDAQAB
-----END PUBLIC KEY-----
KEY;

        // Configuration avec l'algorithme RS256
        $this->jwtConfig = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($publicKey) // Clé publique
        );
    }

    private function fixBase64Url(string $encoded): string
    {
        $remainder = strlen($encoded) % 4;
        if ($remainder > 0) {
            $encoded .= str_repeat('=', 4 - $remainder);
        }
        return str_replace(['-', '_'], ['+', '/'], $encoded);
    }

    public function validateJwt(string $jwt): bool
    {
        try {
            // Réparer le JWT si nécessaire
            $jwt = $this->repairJwt($jwt);

            // Parser le token
            $token = $this->jwtConfig->parser()->parse($jwt);

            // Ajout des contraintes (signature et expiration)
            $constraints = [
                new SignedWith($this->jwtConfig->signer(), $this->jwtConfig->verificationKey()),
                new StrictValidAt(SystemClock::fromUTC()) // Vérifie si le JWT est expiré
            ];

            // Valider le token
            return $this->jwtConfig->validator()->validate($token, ...$constraints);
        } catch (\Exception $e) {
            // Gestion des erreurs (JWT invalide)
            return false;
        }
    }

    private function repairJwt(string $jwt): string
    {
        $parts = explode('.', $jwt);
        if (count($parts) === 3) {
            $parts[0] = $this->fixBase64Url($parts[0]);
            $parts[1] = $this->fixBase64Url($parts[1]);
            return implode('.', $parts);
        }

        return $jwt; // Retourne le JWT inchangé si non réparable
    }


}