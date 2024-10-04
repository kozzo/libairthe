<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function index(): Response
    {
        return $this->render('user/profile.html.twig', [
	        'user' => $this->getUser(),
        ]);
    }

/*	#[Route('/profile', name: 'app_user_profile')]
	public function admin(): Response
	{
		$user = $this->getUser();
		$roles = $user->getRoles();
		$roles.add("ROLE_ADMIN");
		return $this->render('user/profile.html.twig', [
			'user' => $this->getUser(),
		]);
	}*/

}
