<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Travel;
use App\Form\TravelType;
use App\Repository\TravelRepository;
use App\Service\TravelSearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AllowDynamicProperties]
#[Route('/travel')]
class TravelController extends AbstractController
{
	public function __construct(TravelSearchService $travelSearchService) {
		$this->travelSearchService = $travelSearchService;
	}

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

    #[Route('/{id}', name: 'app_travel_page', methods: ['GET'])]
    public function show(Travel $travel): Response
    {
        return $this->render('travel/show.html.twig', [
            'travel' => $travel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_travel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Travel $travel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('travel/edit.html.twig', [
            'travel' => $travel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_travel_delete', methods: ['POST'])]
    public function delete(Request $request, Travel $travel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$travel->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($travel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
    }

	/**
	 * @throws \Exception
	 */
	#[Route('/travels/search', name: 'travel_search')]
	public function search(Request $request): Response
	{
		// Récupérer les critères de recherche depuis la requête
		$criteria = [
			'keyword'   => $request->query->get('keyword'),
			'category'  => $request->query->get('category'),
			'date'      => $request->query->get('date') ? new \DateTime($request->query->get('date')) : null,
			'minRating' => $request->query->get('minRating') ? (int) $request->query->get('minRating') : null,
			'maxBudget' => $request->query->get('maxBudget') ? (float) $request->query->get('maxBudget') : null,
			'adults'    => $request->query->get('adults') ? (int) $request->query->get('adults') : 0,
			'children'  => $request->query->get('children') ? (int) $request->query->get('children') : 0,
		];

		$travels = $this->travelSearchService->searchTravels($criteria);

		return $this->render('travel/search_results.html.twig', [
			'travels' => $travels,
			'criteria' => $criteria,
		]);
	}
}
