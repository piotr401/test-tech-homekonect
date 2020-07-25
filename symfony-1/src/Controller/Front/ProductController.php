<?php

namespace App\Controller\Front;

use App\Utils\Managers\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 *
 * @Route("/produits")
 * @package App\Controller
 */
class ProductController extends AbstractController
{

    /**
     * Homepage
     * @Route("/", name="product_list")
     * @return Response
     */
    public function index(ProductManager $productManager)
    {
        $products = $productManager->findAll();

        return $this->render('@Front/Product/index.html.twig', [
            'products'  => $products
        ]);
    }
}