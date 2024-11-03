<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Enum\ExperienceLevel;
use App\Form\AddressType;
use App\Form\UserFormType;
use App\Form\UserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function index(): Response
    {
        $userExperiencePoints = $this->getUser()->getExperiencePoints();
        $userLevel = ExperienceLevel::getLevelByPoints($userExperiencePoints);

        return $this->render('user/profile.html.twig', [
	        'user' => $this->getUser(),
            'userLevel' => $userLevel,
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

            $slugStreet = str_replace(' ', '-', $address->getStreet());
            $slugCity = $address->getCity();
            $slugCountry = $address->getCountry();

            $slug =
                $address->getZipCode().'-'.
                $slugStreet.'-'.
                $slugCity.'-'.
                $slugCountry.'-'.
                uniqid();
            $address->setSlug(strtolower($slug));


//			$address->setSlug($address->getZipCode().'-'.$address->getStreet().'-'.$address->getCity());

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
	public function deleteAddress(Request $request, AddressRepository $addressRepository, string $slug): Response
	{
		$address = $addressRepository->findOneBy(['slug' => $slug]);

		if ($address && $this->isCsrfTokenValid('delete' . $address->getSlug(), $request->request->get('_token'))) {
			$addressRepository->remove($address, true);
		}

		return $this->redirectToRoute('app_user_profile');
	}

	#[Route('/edit/{slug}', name: 'app_user_crud_edit')]
	public function edit(Request $request, EntityManagerInterface $entityManager): Response
	{
		$user = $this->getUser();
		$form = $this->createForm(UserFormType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();

			return $this->redirectToRoute('app_user_profile');
		}

		return $this->render('user_crud/edit.html.twig', [
			'user' => $user,
			'form' => $form,
		]);
	}

	#[Route('/delete/{slug}', name: 'app_user_crud_delete')]
	public function delete(Request $request, EntityManagerInterface $entityManager): Response
	{
		$user = $this->getUser();
		$user->setDeletedAt(new \DateTimeImmutable());

		return $this->redirectToRoute('app_home');


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
