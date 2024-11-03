<?php

namespace App\Controller;

use App\Entity\Travel;
use App\Service\TravelSearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
	public function __construct(TravelSearchService $travelSearchService, EntityManagerInterface $entityManager) {
		$this->travelSearchService = $travelSearchService;
		$this->entityManager = $entityManager;
	}
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
		$latestTravels =$this->entityManager->getRepository(Travel::class)->findBy([],['createdAt' => 'DESC'],2);

	    return $this->render('home/index.html.twig', [
			'latestTravels' => $latestTravels,
        ]);
    }
}
