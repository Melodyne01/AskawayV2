<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AddUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, array('attr' => ['label' => false, 'class' => 'uk-input']))
            ->add('lastname', TextType::class, array('attr' => ['label' => false, 'class' => 'uk-input']) )
            ->add('email', EmailType::class, array('attr' => ['label' => false, 'class' => 'uk-input']))
            ->add('password', PasswordType::class, array('attr' => ['label' => false, 'class' => 'uk-input']))
            ->add('confirmPassword', PasswordType::class, array('attr' => ['label' => false, 'class' => 'uk-input']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
