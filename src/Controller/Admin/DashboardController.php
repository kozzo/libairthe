<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\Destination;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\Tag;
use App\Entity\Travel;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    private Packages $assets;
    private EntityManagerInterface $entityManager;


    public function __construct(Packages $assets, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->assets = $assets;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)
            ->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
//        $parametersRepository = $this->entityManager->getRepository(Parameters::class);
//        $logoSetting = $parametersRepository->findOneBy(['settingName' => 'Logo']);
//
//        $logoFileName = $logoSetting ? $logoSetting->getFileName() : 'logo.png';
//        $logoPath = 'build/images/logos/' . $logoFileName;

        return Dashboard::new()
            ->setTitle('<img   src="" 
                                    class="img-fluid d-block mx-auto rounded-circle" 
                                    alt="Titre"
                                    aria-label="Titre"
                                    style="max-width:100px; width:100%;">');
//            ->setFaviconPath($this->assets->getUrl($logoPath));
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('General');

        yield MenuItem::linkToCrud('Address', 'fa fa-home', Address::class)
            ->setController(AddressCrudController::class);

        yield MenuItem::linkToCrud('Category', 'fa fa-mountain-sun', Category::class)
            ->setController(CategoryCrudController::class);

        yield MenuItem::linkToCrud('Destination', 'fa fa-location-dot', Destination::class)
            ->setController(DestinationCrudController::class);

        yield MenuItem::linkToCrud('Reservation', 'fa fa-calendar', Reservation::class)
            ->setController(ReservationCrudController::class);

        yield MenuItem::linkToCrud('Review', 'fa fa-star', Review::class)
            ->setController(ReviewCrudController::class);

        yield MenuItem::linkToCrud('Tag', 'fa fa-tag', Tag::class)
            ->setController(TagCrudController::class);

        yield MenuItem::linkToCrud('Travel', 'fa fa-plane', Travel::class)
            ->setController(TravelCrudController::class);


        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class)
            ->setController(UserCrudController::class);
//            ->setPermission("ROLE_ADMIN")

        yield MenuItem::section('Back');
        yield MenuItem::linkToRoute('To Homepage', 'fa fa-door-open', 'app_home');
        yield MenuItem::linkToLogout('Logout', 'fa fa-arrow-right-from-bracket');


    }
}
