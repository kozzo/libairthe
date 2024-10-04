<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
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

	#[Route('/delete-address/{slug}', name: 'app_user_address_delete', methods: ['POST'])]
	public function delete(Request $request, AddressRepository $addressRepository, string $slug): Response
	{
		$address = $addressRepository->findOneBy(['slug' => $slug]);

		if ($address && $this->isCsrfTokenValid('delete' . $address->getSlug(), $request->request->get('_token'))) {
			$addressRepository->remove($address, true);
		}

		return $this->redirectToRoute('app_user_profile');
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
