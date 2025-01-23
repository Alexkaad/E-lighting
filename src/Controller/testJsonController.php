<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class testJsonController extends AbstractController
{
    #[Route('/test-json')]
    public function testJon(SerializerInterface $serializer): Response
    {
        $data = ['name' => 'Flory', 'age' => 25];

        $jsonData = json_encode($data);
        if ($jsonData === false) {
            echo "Erreur: ext-json ne fonctionne pas (json_encode a échoué).\n";
        } else {
            echo "Sérialisation réussie : $jsonData\n";
        }

// Vérification de json_decode
        $decodedData = json_decode($jsonData, true);
        if ($decodedData === null) {
            echo "Erreur: ext-json ne fonctionne pas (json_decode a échoué).\n";
        } else {
            echo "Désérialisation réussie :\n";
            print_r($decodedData);

            return $this->json($decodedData);

        }
        return new Response();
    }

    #[Route('/test-log', name: 'test_log')]
    public function testLog(LoggerInterface $logger): JsonResponse
    {
        // Ajout d'un message dans le log
        $logger->info('Test : écriture du log dev.log réussie.');

        return new JsonResponse(['message' => 'Log écrit avec succès']);
    }

    #[Route('/test-gd', name: 'test_gd')]
    public function testGD(): Response
    {
        // Placer ici le code PHP de diagnostic GD
        $functions = [
            'imagealphablending',
            'imagesavealpha',
            'imagecreatetruecolor',
            'imagecreatefromgif',
            'imagecreatefromjpeg',
            'imagecreatefrompng',
            'imagecopyresampled',
        ];

        $results = [];
        foreach ($functions as $function) {
            $results[$function] = function_exists($function) ? 'Available' : 'Not Available';
        }

        return new Response('<pre>' . print_r($results, true) . '</pre>');
    }

}






