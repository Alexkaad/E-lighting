<?php

namespace App\Controller;

use App\Service\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class JwtController extends AbstractController
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }


     #[Route("/api/validate-jwt", methods: 'GET')]

    public function validateJwt(): JsonResponse
    {
        // Exemple de JWT (remplacez-le par celui que vous voulez tester)
        $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzU0MTM3MDUsImV4cCI6MTczNTQxNzMwNSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImVtYWlsIjoicGljb0BtYWlsLmZyIn0.QhhYqkjIypmiv8JOt0vZXEt7edrSAb9EJIcv4OksNYmask-sS4YTatFJiX2nRo_u2Ceb9Q4cm8EqOBy1mhQvFuSV740T61ZRCW6dmqLaHcZlil7AKp-CJeJw0qDzmdE00iInF87eD6AVc28m3q0e_SK4XJDZVDdHGpvkpROKYWeleplR0OfMjFLPqG-uiZkUL06mtvFSTxGL3qDNSZ_zMYbroQDKLP_ym7LpdV4aV_s5tIs21nLFrXJaCLo_Gg0DSsC8MGXIw5a_NCVnnGM2tY6zssUQwd17KoFL46n-7msV-Nbpljmvd0bggimEkK7nRJ-oHsDnMu10zkRY7J2J0A
}';

        $isValid = $this->jwtService->validateJwt($jwt);

        return new JsonResponse([
            'valid' => $isValid,
            'message' => $isValid ? 'JWT valide !' : 'JWT invalide ou expiré.'
        ]);
    }
}