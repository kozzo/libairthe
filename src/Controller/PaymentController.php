<?php
	namespace App\Controller;

	use App\Entity\Reservation;
	use App\Service\ReservationService;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;

	#[\Symfony\Component\Routing\Attribute\Route('/payment')]
	class PaymentController extends AbstractController
	{
		private ReservationService $reservationService;
		private EntityManagerInterface $entityManager;


		public function __construct(EntityManagerInterface $entityManager, ReservationService $reservationService)
		{
			$this->entityManager = $entityManager;
			$this->reservationService = $reservationService;
		}

		#[Route('/new/{slug}', name: 'app_payment_new', methods: ['GET', 'POST'])]
		public function paymentPage($slug): Response
		{
			$reservation = $this->entityManager->getRepository(Reservation::class)->findOneBy(['slug' => $slug]);

        return $this->render('payment/index.html.twig', [
	        'reservation' => $reservation,
        ]);
    }

		#[Route('/confirm/{slug}', name: 'app_payment_confirm', methods: ['GET', 'POST'])]
		public function confirmPayment($slug): Response
		{
			$this->addFlash('success', 'Votre paiement a été effectué avec succès.');
			$reservation = $this->entityManager->getRepository(Reservation::class)->findOneBy(['slug' => $slug]);
			$reservation->setStatus('Validé et payé');
			$this->entityManager->persist($reservation);
			$this->entityManager->flush();

			return $this->redirectToRoute('app_reservation_summary', ['slug' => $slug]);
		}
	}
