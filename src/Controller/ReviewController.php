<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Service\ReviewService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
	private ReviewService $reviewService;
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager,ReviewService $reviewService)
	{
		$this->reviewService = $reviewService;
		$this->entityManager = $entityManager;

	}

	#[Route('/new/{slug}', name: 'app_review_new', methods: ['GET', 'POST'])]
	public function newReview(Request $request): Response
	{
		$reservation = $this->entityManager->getRepository(Reservation::class)->findOneBy(['slug' => $request->get('slug')]);
		$form = $this->createForm(ReviewType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user = $this->getUser();
			$review = $form->getData();


			$this->reviewService->createReview(
				$reservation,
				$user,
				$review->getNote(),
				$review->getContent()
			);

			$this->addFlash('success', 'Merci pour votre avis !');

			return $this->redirectToRoute('app_user_profile');
		}

		return $this->render('review/new.html.twig', [
			'form' => $form->createView(),
			'reservation' => $reservation,
		]);
	}
}
