<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Destination;
use App\Entity\Tag;
use App\Entity\Travel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
	    $builder
		    ->add('keyword', TextType::class, [
			    'label' => 'Destination',
			    'required' => false,
		    ])
		    ->add('category', EntityType::class, [
			    'class' => Category::class,
			    'choice_label' => 'name',
			    'label' => 'Catégorie',
			    'required' => false,
		    ])
		    ->add('date', DateType::class, [
			    'widget' => 'single_text',
			    'label' => 'Date de départ',
			    'required' => false,
		    ])
		    ->add('minRating', IntegerType::class, [
			    'label' => 'Note minimum',
			    'required' => false,
		    ])
		    ->add('adults', IntegerType::class, [
			    'label' => 'Nombre d\'adultes',
			    'required' => true,
		    ])
		    ->add('children', IntegerType::class, [
			    'label' => 'Nombre d\'enfants',
			    'required' => true,
		    ])
		    ->add('maxBudget', NumberType::class, [
			    'label' => 'Budget maximal',
			    'required' => false,
		    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Travel::class,
        ]);
    }
}
