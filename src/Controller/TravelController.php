<?php

namespace App\Controller;

use App\Entity\Travel;
use App\Form\TravelType;
use App\Repository\TravelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/travel')]
final class TravelController extends AbstractController
{

    #[Route('/', name: 'app_travel')]
    public function index(TravelRepository $travelRepository): Response
    {
        $catalog = $travelRepository->findAll();

        return $this->render('travel/index.html.twig', [
            'controller_name' => 'TravelController',
            'catalog' => $catalog,
        ]);

    }


    #[Route('/new', name: 'app_travel_add', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $travel = new Travel();
        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($travel);
            $entityManager->flush();

            return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('travel/new.html.twig', [
            'travel' => $travel,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_travel_page', methods: ['GET'])]
    public function show(TravelRepository $travelRepository, string $slug): Response
    {
        $travel = $travelRepository->findOneBy(['slug' => $slug]);

        if (!$travel) {
            throw $this->createNotFoundException('Travel page not found');
        }

        return $this->render('travel/show.html.twig', [
            'travel' => $travel,
        ]);
    }


}
