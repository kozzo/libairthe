<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/CGV', name: 'app_cgv')]
    public function index(): Response
    {
        return $this->render('legal/CGV.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }
}
