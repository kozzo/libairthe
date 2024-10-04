<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
        ]);
    }

    #[Route('/FAQ', name: 'app_FAQ')]
    public function faq(): Response
    {
        return $this->render('home/FAQ.html.twig', [
        ]);
    }

    #[Route('/About_Us', name: 'app_About_Us')]
    public function aboutUs(): Response
    {
        return $this->render('home/FAQ.html.twig', [
        ]);
    }
}
