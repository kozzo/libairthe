<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Regex;

class AddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Address::class;
    }

    public function createEntity(string $entityFqcn) : Address
    {
        $address = new Address();
        $address->setCreatedAt(new \DateTimeImmutable());
        $address->setUpdatedAt(new \DateTimeImmutable());
        $address->setSlug($address->getZipCode().'-'.$address->getStreet().'-'.$address->getCity().'-'.$address->getCountry());
        return $address;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        $entityInstance->setSlug(
            $entityInstance->getZipCode().'-'.
            $entityInstance->getStreet().'-'.
            $entityInstance->getCity().'-'.
            $entityInstance->getCountry());
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

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
            TextField::new('street')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\' ]{2,}+$/',
                            'message' => 'Characters only'
                        ]),
                    ],
                ]),
            NumberField::new('zipCode')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^\d{5}$/',
                            'message' => '5 numbers only'
                        ]),
                    ],
                ]),
            TextField::new('city')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\' ]{2,}+$/',
                            'message' => 'Characters only'
                        ]),
                    ],
                ]),
            TextField::new('country')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\' ]{2,}+$/',
                            'message' => 'Characters only'
                        ]),
                    ],
                ]),
            TextField::new('phone')
                ->setFormTypeOptions([
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
                            'message' => 'Please enter a valid phone number.'
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
