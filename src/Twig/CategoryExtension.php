<?php
	namespace App\Twig;

	use App\Service\CategoryService;
	use Twig\Extension\AbstractExtension;
	use Twig\TwigFunction;

	class CategoryExtension extends AbstractExtension
	{
		private CategoryService $categoryService;

		public function __construct(CategoryService $categoryService)
		{
			$this->categoryService = $categoryService;
		}

		public function getFunctions(): array
		{
			return [
				new TwigFunction('get_all_categories', [$this, 'getAllCategories']),
			];
		}

		public function getAllCategories(): array
		{
			return $this->categoryService->getAllCategories();
		}
	}
