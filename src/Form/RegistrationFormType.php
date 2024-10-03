<?php

	namespace App\Form;

	use App\Entity\User;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints\IsTrue;
	use Symfony\Component\Validator\Constraints\Length;
	use Symfony\Component\Validator\Constraints\NotBlank;

	class RegistrationFormType extends AbstractType
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
				->add('email', TextType::class, [
					'attr' => [
						'class' => 'form-control mb-2',
						'id' => 'email',
					],
					'label' => 'Adresse mail',
					'label_attr' => [
						'class' => 'form-label',
					],
				])
				->add('agreeTerms', CheckboxType::class, [
					'mapped' => false,
					'label' => 'J\'accepte les conditions générales d\'utilisation',
					'attr' => [
						'class' => 'form-check checkbox-lg mb-2',
					],
					'constraints' => [
						new IsTrue([
							'message' => 'Vous devez accepter les règles générales d\'utilisation.',
						]),
					],
				])
				->add('plainPassword', PasswordType::class, [
					// instead of being set onto the object directly,
					// this is read and encoded in the controller
					'mapped' => false,
					'attr' => [
						'autocomplete' => 'new-password',
						'class' => 'form-control mb-2'
					],
					'constraints' => [
						new NotBlank([
							'message' => 'Please enter a password',
						]),
						new Length([
							'min' => 8,
							'minMessage' => 'Your password should be at least {{ limit }} characters',
							// max length allowed by Symfony for security reasons
							'max' => 4096,
						]),
					],
				]);
		}

		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => User::class,
			]);
		}
	}
