<?php

namespace App\Utils\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Utils\Managers\ProductManager;

class CartService
{
    private $session;
    private $cart;
    private $productManager;

    public function __construct(SessionInterface $session, ProductManager $productManager)
    {
        $this->session = $session;
        $this->productManager = $productManager;
        $this->cart = unserialize($this->session->get('shopping_cart', serialize([])));
    }

    public function add($ref)
    {
        $product = $this->productManager->findOneBy(['ref' => $ref]);

        if (key_exists($ref, $this->cart)) {
            $this->cart[$ref]['quantity'] = $this->cart[$ref]['quantity'] + 1;
        } else {
            $this->cart[$ref]['quantity'] = 1;
        }

        $this->cart[$ref]['title'] = $product->getTitle();
        $this->cart[$ref]['price'] = $product->getPrice();
        $this->cart[$ref]['prodTotal'] =  $this->cart[$ref]['quantity'] * $this->cart[$ref]['price'];

        $this->session->set('shopping_cart', serialize($this->cart));

        $infos = $this->getTotal();

        return [
            'info'    =>   $infos,
            'cart'    => $this->cart
        ];

    }

    public function remove($ref)
    {
        $this->cart[$ref]['quantity'] = $this->cart[$ref]['quantity'] - 1;
        $this->cart[$ref]['prodTotal'] =  $this->cart[$ref]['quantity'] * $this->cart[$ref]['price'];

        $this->session->set('shopping_cart', serialize($this->cart));

        $infos = $this->getTotal();

        return [
            'info'    =>   $infos,
            'cart'    =>   $this->cart
        ];
    }

    public function delete($ref)
    {
        unset($this->cart[$ref]);

        $this->session->set('shopping_cart', serialize($this->cart));

        $infos = $this->getTotal();

        return [
            'info'    =>   $infos,
            'cart'    =>   $this->cart
        ];
    }

    public function getTotal()
    {
        $infos['total'] = 0;
        $infos['totalQuantity'] = 0;
        foreach ($this->cart as $product) {
            $infos['total'] = $infos['total'] + $product['prodTotal'];
            $infos['totalQuantity'] = $infos['totalQuantity'] + $product['quantity'];
        }
        return $infos;
    }

    public function getCart()
    {
        $infos = $this->getTotal();
        return [
            'info'    =>   $infos,
            'cart'    =>   $this->cart
        ];
    }
}