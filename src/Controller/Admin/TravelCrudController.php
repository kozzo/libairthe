<?php

namespace App\Controller\Admin;

use App\Entity\Travel;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class TravelCrudController extends AbstractCrudController implements EventSubscriberInterface
{

    public static function getEntityFqcn(): string
    {
        return Travel::class;
    }

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setTravelSlug'],
        ];
    }

    public function setTravelSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Travel)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getLabel());
        $entity->setSlug(strtolower($slug));
    }

    public function createEntity($entityFqcn): Travel
    {
        $travel = new Travel();

        $travel->setCreatedAt(new \DateTimeImmutable());
        $travel->setUpdatedAt(new \DateTimeImmutable());

        return $travel;
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Travel) {
            $slug = $this->slugger->slug($entityInstance->getLabel());
            $entityInstance->setSlug(strtolower($slug));
        }

        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnIndex()
                ->hideOnForm(),
            TextField::new('slug')
                ->hideOnForm(),
            TextField::new('label')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9%()\-\' ]{2,}+$/',
                            'message' => 'Label needs at least 2 characters.'
                        ]),
                    ],
                ]),
            MoneyField::new('adultUnitPrice')
                ->setCurrency('EUR'),
            MoneyField::new('childUnitPrice')
                ->setCurrency('EUR'),
            NumberField::new('totalSeats'),
            NumberField::new('availableSeats'),
            NumberField::new('dailySeats'),
            IntegerField::new('note')
	            ->hideOnForm(),
            TextField::new('description')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9%()!?:\' ]{2,}+$/',
                            'message' => 'Needs at least 2 characters.'
                        ]),
                    ],
                ]),
            TextField::new('departurePlace')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9%()\' ]{2,}+$/',
                            'message' => 'Needs at least 2 characters.'
                        ]),
                    ],
                ]),
            TextField::new('arrivingPlace')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9%()\' ]{2,}+$/',
                            'message' => 'Needs at least 2 characters.'
                        ]),
                    ],
                ]),
            AssociationField::new('categories', 'Category')
                ->setTemplatePath('admin/display_relations.html.twig')
                ->setCrudController(CategoryCrudController::class)
                ->setFormTypeOptions([
                    'constraints' => [
                        new Count([
                            'min' => 1,
                            'minMessage' => 'Please select at least one category.',
                        ]),
                    ],
                ]),
            AssociationField::new('destination', 'Destination')
                ->setCrudController(DestinationCrudController::class)
                ->setFormTypeOptions([
                    'constraints' => [
                        new NotNull([
                            'message' => 'Please select a destination.',
                        ]),
                    ],
                ]),
            AssociationField::new('tags', 'Tag')
                ->setTemplatePath('admin/display_relations.html.twig')
                ->setCrudController(TagCrudController::class)
                ->setFormTypeOptions([
                    'constraints' => [
                        new Count([
                            'min' => 0,
                        ]),
                    ],
                ]),

            DateField::new('createdAt')
                ->hideOnForm(),
	        DateField::new('periodStart'),
	        DateField::new('periodEnd')
        ];
    }
}
