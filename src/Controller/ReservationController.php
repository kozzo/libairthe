<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Reservation;
use App\Entity\Travel;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
	private ReservationService $reservationService;
	private EntityManagerInterface $entityManager;


	public function __construct(EntityManagerInterface $entityManager, ReservationService $reservationService)
	{
		$this->entityManager = $entityManager;
		$this->reservationService = $reservationService;
	}

	#[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

	#[Route('/new/{slug}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
	public function new(Request $request, string $slug): Response
	{
		/** @var User $client */
		$client = $this->getUser();
		$addresses = $this->entityManager->getRepository(Address::class)->findBy(['client' => $client]);
		$travel = $this->entityManager->getRepository(Travel::class)->findOneBy(['slug' => $slug]);

		$form = $this->createForm(ReservationType::class, null, [
			'address_choices' => $addresses,
			'travel' => $travel,
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$address = $form->get('address')->getData();

			try {
				$reservation = $this->reservationService->createReservation($request, $travel, $address, $client);
				if (!$reservation->getSlug()) {
					throw $this->createNotFoundException('Reservation slug not found.');
				}
				return $this->redirectToRoute('app_reservation_summary', ['slug' => $reservation->getSlug()], Response::HTTP_SEE_OTHER);
			} catch (\Exception $e) {
				$this->addFlash('error', 'Une erreur s\'est produite lors de la réservation : ' . $e->getMessage());
			}
		}

		return $this->render('reservation/new.html.twig', [
			'travel' => $travel,
			'form' => $form->createView(),
		]);
	}

	#[Route('/show/{slug}', name: 'app_reservation_summary', methods: ['GET'])]
	public function show(Reservation $reservation): Response
	{
		return $this->render('reservation/show.html.twig', [
			'reservation' => $reservation,
		]);
	}

    #[Route('/{slug}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getSlug(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
