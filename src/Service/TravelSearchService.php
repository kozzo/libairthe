<?php

	namespace App\Service;

	use App\Repository\TravelRepository;
	use Doctrine\ORM\QueryBuilder;
	use DateTimeInterface;

	class TravelSearchService
	{
		private TravelRepository $travelRepository;

		public function __construct(TravelRepository $travelRepository)
		{
			$this->travelRepository = $travelRepository;
		}

		public function searchTravels(array $criteria): array
		{
			$qb = $this->travelRepository->createQueryBuilder('t');

			$this->applyTextSearch($qb, $criteria['keyword'] ?? null);
			$this->applyCategoryFilter($qb, $criteria['category'] ?? null);
			$this->applyDateFilter($qb, $criteria['date'] ?? null);
			$this->applyRatingFilter($qb, $criteria['minRating'] ?? null);
			$this->applyBudgetFilter($qb, $criteria['maxBudget'] ?? null, $criteria['adults'] ?? 0, $criteria['children'] ?? 0);

			return $qb->getQuery()->getResult();
		}

		private function applyTextSearch(QueryBuilder $qb, ?string $keyword): void
		{
			if ($keyword) {
				$qb->andWhere('t.label LIKE :keyword OR t.arrivingPlace LIKE :keyword')
					->setParameter('keyword', '%' . $keyword . '%');
			}
		}

		private function applyCategoryFilter(QueryBuilder $qb, $category): void
		{
			if ($category) {
				$qb->join('t.categories', 'c')
					->andWhere('c.id = :category')
					->setParameter('category', $category);
			}
		}

		private function applyDateFilter(QueryBuilder $qb, ?DateTimeInterface $date): void
		{
			if ($date) {
				$qb->andWhere(':date BETWEEN t.periodStart AND t.periodEnd')
					->setParameter('date', $date);
			}
		}

		private function applyRatingFilter(QueryBuilder $qb, ?int $minRating): void
		{
			if ($minRating) {
				$qb->andWhere('t.note >= :minRating')
					->setParameter('minRating', $minRating);
			}
		}

		private function applyBudgetFilter(QueryBuilder $qb, ?float $maxBudget, int $adults, int $children): void
		{
			if ($maxBudget !== null && ($adults > 0 || $children > 0)) {
				$qb->andWhere('(:totalPrice) <= :maxBudget')
					->setParameter('totalPrice', ($adults * 't.adultUnitPrice') + ($children * 't.childUnitPrice'))
					->setParameter('maxBudget', $maxBudget);
			}
		}
	}
