<?php

namespace App\Controller\Front;

use App\Utils\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $cart = $cartData['cart'];
        $info = $cartData['info'];

        return $this->render('@Front/Cart/index.html.twig', [
            'cart'      => $cart,
            'info'      => $info
        ]);
    }

    /**
     * manage cart
     * @Route("/live/ajax-manage-cart", name="manage_cart")
     * @return JsonResponse
     */
    public function ajaxManageCart(Request $request, CartService $cartService)
    {
        $type = $request->request->get('type', 'add');
        $ref = $request->request->get('ref', null);

        if ($request->isXmlHttpRequest() && $ref) {
            if ($type === 'add') {
                $cartService->add($ref);
            } elseif ($type === 'remove') {
                $cartService->remove($ref);
            } elseif ($type === 'delete') {
                $cartService->delete($ref);
            } else {
                return new JsonResponse([
                    'status'    =>  400
                ]);
            }
            return new JsonResponse([
                'status'    =>  200
            ]);
        }
        return new JsonResponse([
            'status'    =>  400
        ]);
    }
}