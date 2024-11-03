<?php

	namespace App\Service;

	use App\Entity\User;
	use Doctrine\ORM\EntityManagerInterface;

	class NewsletterService
	{
		private EntityManagerInterface $entityManager;

		public function __construct(EntityManagerInterface $entityManager)
		{
			$this->entityManager = $entityManager;
		}

		public function subscribeUserByEmail(string $email): void
		{
			$user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

			if ($user && !$user->isSubscribedToNewsletter()) {
				$user->setSubscribedToNewsletter(true);
				$this->entityManager->flush();
			}
		}
	}
