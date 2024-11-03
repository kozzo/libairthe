<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
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

class ReservationCrudController extends AbstractCrudController implements EventSubscriberInterface
{

    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setReservationSlug'],
        ];
    }

    public function setReservationSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Reservation)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getReference());
        $entity->setSlug(strtolower($slug));
    }

    public function createEntity($entityFqcn): Reservation
    {
        $reservation = new Reservation();

        $reservation->setReference(uniqid('#'));
        $reservation->setCreatedAt(new \DateTimeImmutable());
        $reservation->setUpdatedAt(new \DateTimeImmutable());

        return $reservation;
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Reservation) {
            $slug = $this->slugger->slug($entityInstance->getReference());
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
            TextField::new('reference')
                ->hideOnForm(),
            NumberField::new('adultTraveler'),
            NumberField::new('childTraveler'),
            MoneyField::new('totalPrice')
                ->setCurrency('EUR'),
            TextField::new('status'),
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
            AssociationField::new('address', 'Address')
                ->setCrudController(AddressCrudController::class)
                ->setFormTypeOptions([
                    'constraints' => [
                        new NotNull([
                            'message' => 'Please select at least one address.',
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
