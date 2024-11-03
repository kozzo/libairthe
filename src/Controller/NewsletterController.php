<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\User;
use App\Service\NewsletterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class NewsletterController extends AbstractController
{
	private NewsletterService $newsletterService;

	public function __construct(NewsletterService $newsletterService)
	{
		$this->newsletterService = $newsletterService;
	}

	#[Route('/newsletter/subscribe', name: 'newsletter_subscribe', methods: ['POST'])]
	public function subscribe(Request $request): Response
	{
		$email = $request->request->get('email');

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->addFlash('error', 'Adresse e-mail invalide');
		}

		$this->newsletterService->subscribeUserByEmail($email);

		return $this->redirectToRoute('app_home');
	}
}
