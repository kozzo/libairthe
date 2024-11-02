<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

	#[Route('/add_address', name: 'app_user_add_address')]
	public function addAddress(Request $request, EntityManagerInterface $entityManager): Response
	{
		$address = new Address();
		$user = $this->getUser();
		$form = $this->createForm(AddressType::class, $address);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {


            /////
            ///
            ///         $slugStreet = $this->slugger->slug($entity->getStreet());
            //        $slugCity = $this->slugger->slug($entity->getCity());
            //        $slugCountry = $this->slugger->slug($entity->getCountry());
            //
            //        $slug = $this->slugger->slug(
            //            $entity->getZipCode().'-'.
            //                $slugStreet.'-'.
            //                $slugCity.'-'.
            //                $slugCountry
            //        );
            //        $entity->setSlug(strtolower($slug));
            /// /////
			$address->setSlug($address->getZipCode().'-'.$address->getStreet().'-'.$address->getCity());

			$address->setCreatedAt(new \DateTimeImmutable());
			$address->setUpdatedAt(new \DateTimeImmutable());

			$address->setClient($user);

			$entityManager->persist($address);

			$user->addAddress($address);
			$entityManager->persist($user);

			$entityManager->flush();

			return $this->redirectToRoute('app_user_profile');
		}

		return $this->render('user/add-address.html.twig', [
			'user' => $this->getUser(),
			'addressForm' => $form,
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
