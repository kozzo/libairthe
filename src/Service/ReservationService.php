<?php
	namespace App\Service;

	use App\Entity\Reservation;
	use App\Entity\Travel;
	use App\Entity\Address;
	use App\Entity\User;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Request;

	class ReservationService
	{
		private EntityManagerInterface $entityManager;

		public function __construct(EntityManagerInterface $entityManager)
		{
			$this->entityManager = $entityManager;
		}

		/**
		 * @throws \Exception
		 */
		public function checkAvailability(int $requestedSeats, int $availableSeats, int $dailySeats): bool
		{
			if ($requestedSeats > $availableSeats || $requestedSeats > $dailySeats) {
				return false;
			}
			return true;
		}

		/**
		 * @throws \Exception
		 */
		public function createReservation(Request $request, Travel $travel, Address $address, User $client, int $requestedSeats, int $availableSeats, int $dailySeats): Reservation
		{
			$request = $request->get('reservation');
			$adultTraveler = $request['adultTraveler'];
			$childTraveler = $request['childTraveler'];
			$departureDate = $request['departureDate'];

			$totalPrice = $this->calculateTotalPrice($adultTraveler, $childTraveler, $travel);

			$reservation = new Reservation();
			$reservation->setSlug(uniqid('reservation_'));
			$reservation->setReference(uniqid('ref_'));
			$reservation->setAdultTraveler($adultTraveler);
			$reservation->setChildTraveler($childTraveler);
			$reservation->setTotalPrice($totalPrice);
			$reservation->setStatus('pending');
			$reservation->setCreatedAt(new \DateTimeImmutable());
			$reservation->setUpdatedAt(new \DateTimeImmutable());
			$reservation->setTravel($travel);
			$reservation->setAddress($address);
			$reservation->setClient($client);
			$reservation->setDepartureDate(new \DateTime($departureDate));

			$travel->setAvailableSeats($travel->getAvailableSeats() - $requestedSeats);
			$travel->setDailySeats($travel->getDailySeats() - $requestedSeats);

			$client->setExperiencePoints($client->getExperiencePoints() + ($totalPrice/1000));

			$this->entityManager->persist($reservation);
			$this->entityManager->persist($travel);
			$this->entityManager->flush();

			return $reservation;
		}

		private function calculateTotalPrice(int $adultTraveler, int $childTraveler, Travel $travel): float
		{
			$adultPrice = $travel->getAdultUnitPrice() * $adultTraveler;
			$childPrice = $travel->getChildUnitPrice() * $childTraveler;

			return ($adultPrice) + ($childPrice);
		}
	}
