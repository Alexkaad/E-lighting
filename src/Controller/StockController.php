<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Stock;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/stock')]
class StockController extends AbstractController
{
    private StockRepository $stockRepository;
    private ProductRepository $productRepository;


    public function __construct(

        StockRepository $stockRepository,
        ProductRepository $productRepository
    )
    {
        $this->stockRepository = $stockRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/add', methods: ['POST'])]
    public function addStock(Stock $stock, int $product): JsonResponse
    {
        $product = $this->productRepository->findProductById($product);
        if(!$product)
        {
            return $this->json('Product not found', 404);
        }
        $this->stockRepository->persist($stock);
        return $this->json('Stock created', 201);
    }

}