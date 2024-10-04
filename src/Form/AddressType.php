<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        ->add('firstName', TextType::class, [
		        'attr' => [
			        'class' => 'form-control mb-2',
			        'id' => 'firstName',
		        ],
		        'label' => 'Prénom',
		        'label_attr' => [
			        'class' => 'form-label',
		        ],
	        ])
	        ->add('lastName', TextType::class, [
		        'attr' => [
			        'class' => 'form-control mb-2',
			        'id' => 'lastName',
		        ],
		        'label' => 'Nom',
		        'label_attr' => [
			        'class' => 'form-label',
		        ],
	        ])
            ->add('street', TextType::class, [
	            'attr' => [
		            'class' => 'form-control mb-2',
		            'id' => 'street',
	            ],
	            'label' => 'Rue',
	            'label_attr' => [
		            'class' => 'form-label',
	            ],
            ])
            ->add('city', TextType::class, [
	            'attr' => [
		            'class' => 'form-control mb-2',
		            'id' => 'city',
	            ],
	            'label' => 'Ville',
	            'label_attr' => [
		            'class' => 'form-label',
	            ],
            ])
            ->add('zipCode', NumberType::class, [
	            'attr' => [
		            'class' => 'form-control mb-2',
		            'id' => 'zipCode',
	            ],
	            'label' => 'Code postal',
	            'label_attr' => [
		            'class' => 'form-label',
	            ],
            ])
            ->add('country', TextType::class, [
	            'attr' => [
		            'class' => 'form-control mb-2',
		            'id' => 'country',
	            ],
	            'label' => 'Pays',
	            'label_attr' => [
		            'class' => 'form-label',
	            ],
            ])
            ->add('phone', TextType::class, [
	            'attr' => [
		            'class' => 'form-control mb-2',
		            'id' => 'phone',
	            ],
	            'label' => 'Numéro de téléphone',
	            'label_attr' => [
		            'class' => 'form-label',
	            ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
