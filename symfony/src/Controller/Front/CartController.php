<?php

namespace App\Controller\Front;

use App\Utils\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 *
 * @Route("/panier")
 * @package App\Controller
 */
class CartController extends AbstractController
{

    /**
     * Homepage
     * @Route("/", name="cart")
     * @return Response
     */
    public function index(CartService $cartService)
    {
        $cartData = $cartService->getCart();

        return $this->render('@Front/Cart/index.html.twig', [
        ]);
    }
}