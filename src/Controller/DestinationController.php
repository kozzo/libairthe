<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Form\DestinationType;
use App\Repository\CategoryRepository;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/destination')]
final class DestinationController extends AbstractController
{
    #[Route('/', name: 'app_destination')]
    public function index(DestinationRepository $destinationRepository): Response
    {
        $catalog = $destinationRepository->findAll();

        return $this->render('destination/index.html.twig', [
            'controller_name' => 'DestinationController',
            'catalog' => $catalog,
        ]);

    }

    #[Route('/new', name: 'app_destination_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $destination = new Destination();
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($destination);
            $entityManager->flush();

            return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('destination/new.html.twig', [
            'destination' => $destination,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_destination_travels')]
    public function showDestinationProducts(DestinationRepository $destinationRepository, $slug): Response
    {
        $destination = $destinationRepository->findOneBy(['slug' => $slug]);

        if (!$destination) {
            throw $this->createNotFoundException('Destination not found');
        }

        $destinationTravels = $destination->getTravels();

        return $this->render('destination/travels.html.twig', [
            'destinationTravels' => $destinationTravels,
            'destination' => $destination,
        ]);
    }

}
