<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\TravelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $catalog = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'catalog' => $catalog,
        ]);

    }

    #[Route('/{slug}', name: 'app_category_travels')]
    public function showCategoryProducts(CategoryRepository $categoryRepository, $slug): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $categoryTravels = $category->getTravels();

        return $this->render('category/travels.html.twig', [
            'categoryTravels' => $categoryTravels,
            'category' => $category,
        ]);
    }


}
