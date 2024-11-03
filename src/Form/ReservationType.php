<?php

	namespace App\Form;

	use App\Entity\Address;
	use App\Entity\Reservation;

	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\IntegerType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;

	class ReservationType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('adultTraveler', IntegerType::class, [
					'required' => true,
					'attr' => [
						'min' => 1,
						'class' => 'form-control mb-3',
						'placeholder' => 'Nombre d\'adultes',
					],
					'label' => 'Adultes',
				])
				->add('childTraveler', IntegerType::class, [
					'required' => true,
					'attr' => [
						'min' => 0,
						'class' => 'form-control mb-3',
						'placeholder' => 'Nombre d\'enfants',
					],
					'label' => 'Enfants',
				])
				->add('departureDate', null, [
					'widget' => 'single_text',
					'attr' => [
						'min' => $options['travel']->getPeriodStart()->format('Y-m-d'),
						'max' => $options['travel']->getPeriodEnd()->format('Y-m-d'),
						'class' => 'form-control mb-3',
						'placeholder' => 'Date de départ',
					],
					'label' => 'Date de départ',
				])
				->add('address', EntityType::class, [
					'class' => Address::class,
					'choice_label' => function (Address $address) {
						return (string) $address;
					},
					'choices' => $options['address_choices'],
					'attr' => [
						'class' => 'form-control mb-3',
					],
					'label' => 'Adresse',
				]);
		}


		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => Reservation::class,
				'address_choices' => [],
				'travel' => null,
			]);
		}
	}
