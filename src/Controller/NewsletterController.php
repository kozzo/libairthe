<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\NewsletterSubscriber;

class NewsletterController extends AbstractController
{
	#[Route('/newsletter/subscribe', name: 'newsletter_subscribe', methods: ['POST'])]
	public function subscribe(Request $request, EntityManagerInterface $entityManager): Response
	{
		$email = $request->request->get('email');

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// L'adresse e-mail est invalide
			$this->addFlash('error', 'Adresse e-mail invalide');
			return $this->redirectToRoute('homepage'); // Redirigez vers la page souhaitée
		}

		// Vérifiez si l'e-mail est déjà inscrit
		$existingSubscriber = $entityManager->getRepository(NewsletterSubscriber::class)->findOneBy(['email' => $email]);
		if ($existingSubscriber) {
			$this->addFlash('info', 'Vous êtes déjà inscrit à la newsletter');
			return $this->redirectToRoute('homepage');
		}

		// Ajouter l'adresse e-mail à la base de données
		$subscriber = new NewsletterSubscriber();
		$subscriber->setEmail($email);
		$subscriber->setSubscribedAt(new \DateTime());

		$entityManager->persist($subscriber);
		$entityManager->flush();

		$this->addFlash('success', 'Inscription réussie à la newsletter');
		return $this->redirectToRoute('homepage');
	}
}
