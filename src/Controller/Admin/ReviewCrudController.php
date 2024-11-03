<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class ReviewCrudController extends AbstractCrudController implements EventSubscriberInterface
{

    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setReviewSlug'],
        ];
    }

    public function setReviewSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Review)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getNote());
        $entity->setSlug(strtolower($slug));
    }

    public function createEntity($entityFqcn): Review
    {
        $review = new Review();

        $review->setCreatedAt(new \DateTimeImmutable());
        $review->setUpdatedAt(new \DateTimeImmutable());

        return $review;
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Review) {
            $slug = $this->slugger->slug($entityInstance->getNote());
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
            NumberField::new('note')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[0-5]{1}$/',
                            'message' => 'Note needs to be between 0 and 5.'
                        ]),
                    ],
                ]),
            TextareaField::new('content'),
            AssociationField::new('client', 'User')
                ->setCrudController(UserCrudController::class)
                ->setFormTypeOptions([
                    'constraints' => [
                        new NotNull([
                            'message' => 'Please select at least one user.',
                        ]),
                    ],
                ]),
            AssociationField::new('travel', 'Travel')
                ->setCrudController(TravelCrudController::class)
                ->setFormTypeOptions([
                    'constraints' => [
                        new NotNull([
                            'message' => 'Please select at least one travel.',
                        ]),
                    ],
                ]),
            DateField::new('createdAt')
                ->hideOnForm(),
            DateField::new('updatedAt')
                ->hideOnForm(),
            DateField::new('deletedAt')
                ->hideOnForm(),
        ];
    }
}
