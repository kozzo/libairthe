<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/CGV', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('legal/CGV.html.twig', [
        ]);
    }
    #[Route('/legal_notice', name: 'app_legal_notice')]
    public function legalNotice(): Response
    {
        return $this->render('legal/legalNotice.html.twig', [
        ]);
    }

}
