<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\favorites;
use App\Repository\FavoritesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavoritesController extends AbstractController
{

    public function __construct(FavoritesRepository $favoritesRepository){

        $this->favoritesRepository = $favoritesRepository;
    }
    #[Route('/favorites')]
    public function addFavorites(int $productId, int $userId, int $favorites): Response
    {
        if($this->favoritesRepository->isProductInFavorites($productId, $userId))
        {

        }
    }
}
