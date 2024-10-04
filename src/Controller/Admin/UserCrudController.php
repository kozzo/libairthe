<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Regex;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setSlug($entityInstance->getFirstName().'-'.$entityInstance->getLastName());
        $entityInstance->setUpdatedAt(new \DateTime());
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('email')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/',
                            'message' => 'Please enter a valid email address',
                        ]),
                    ],
                ]),
//             ->hideOnIndex(),
            TextField::new('firstName')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\' ]{2,}+$/',
                            'message' => 'Characters only'
                        ]),
                    ],
                ]),
            TextField::new('lastName')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\' ]{2,}+$/',
                            'message' => 'Characters only'
                        ]),
                    ],
                ]),
            DateField::new('createdAt')
                ->hideOnForm(),
            DateField::new('updatedAt')
                ->hideOnForm(),
            DateField::new('deletedAt')
                ->hideOnForm(),
            BooleanField::new('isAdmin')
        ];
    }

}
