<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Regex;

class TagCrudController extends AbstractCrudController implements EventSubscriberInterface
{

    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setTagSlug'],
        ];
    }

    public function setTagSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Tag)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getLabel());
        $entity->setSlug(strtolower($slug));
    }

    public function createEntity($entityFqcn): Tag
    {
        $tag = new Tag();

        return $tag;
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Tag) {
            $slug = $this->slugger->slug($entityInstance->getLabel());
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
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9%()\-\' ]{2,}+$/',
                            'message' => 'Label needs at least 2 characters.'
                        ]),
                    ],
                ]),
            ColorField::new('color'),
            TextField::new('content')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9%!?()\' ]{2,}+$/',
                            'message' => 'Content needs at least 2 characters.'
                        ]),
                    ],
                ]),

        ];
    }
}
