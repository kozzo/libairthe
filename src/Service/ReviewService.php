<?php
	namespace App\Service;

	use App\Entity\Review;
	use App\Entity\Reservation;
	use App\Entity\User;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\String\Slugger\SluggerInterface;

	class ReviewService
	{
		private EntityManagerInterface $entityManager;
		private SluggerInterface $slugger;

		public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
		{
			$this->entityManager = $entityManager;
			$this->slugger = $slugger;
		}

		public function createReview(Reservation $reservation, User $user, int $note, string $content): Review
		{
			$review = new Review();
			$review->setReservation($reservation);
			$review->setTravel($reservation->getTravel());
			$review->setClient($user);
			$review->setNote($note);
			$review->setContent($content);
			$review->setCreatedAt(new \DateTimeImmutable());
			$review->setUpdatedAt(new \DateTimeImmutable());
			$review->setSlug($this->slugger->slug($reservation->getTravel()->getLabel() . '-' . uniqid())->lower());

			$this->entityManager->persist($review);
			$this->entityManager->flush();

			return $review;
		}
	}
