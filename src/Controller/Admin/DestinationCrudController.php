<?php

namespace App\Controller\Admin;

use App\Entity\Destination;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Regex;

class DestinationCrudController extends AbstractCrudController implements EventSubscriberInterface
{

    public static function getEntityFqcn(): string
    {
        return Destination::class;
    }

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setDestinationSlug'],
        ];
    }

    public function setDestinationSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Destination)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getLabel().'-'.$entity->getCountry());
        $entity->setSlug(strtolower($slug));
    }

    public function createEntity($entityFqcn): Destination
    {
        $destination = new Destination();

        return $destination;
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Destination) {
            $slug = $this->slugger->slug($entityInstance->getLabel().'-'.$entityInstance->getCountry());
            $entityInstance->setSlug(strtolower($slug));
        }
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
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\' ]{2,}+$/',
                            'message' => 'Characters only.'
                        ]),
                    ],
                ]),
            TextField::new('country')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\' ]{2,}+$/',
                            'message' => 'Characters only.'
                        ]),
                    ],
                ]),

        ];
    }
}
