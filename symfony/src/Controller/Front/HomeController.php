<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @Route("/")
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * Homepage
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index()
    {
        return $this->render('@Front/Home/index.html.twig', [
        ]);
    }
}