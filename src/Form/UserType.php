<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
  // src/Form/UserType.php

public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('fullname', TextType::class, [
            'label' => 'Full Name',
            'required' => true,
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'required' => true,
        ])
        ->add('phoneNumber', TextType::class, [
            'label' => 'Phone Number',
            'required' => false,
        ])
        ->add('password', PasswordType::class, [
            'label' => 'Password',
            'required' => true,
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Register',
        ]);
}


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Ensure it's mapping to User entity
        ]);
    }
}
