<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('email', EmailType::class)
        ->add('nom', TextType::class)
        ->add('prenom', TextType::class);

    if ($options['is_admin']) {
        $builder->add('actif', CheckboxType::class, [
            'required' => false,
            'label' => 'Actif'
        ]);
    }

    $builder->add('plainPassword', PasswordType::class, [
        'mapped' => false,
        'label' => 'Mot de passe'
    ]);
}


public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([
        'data_class' => User::class,
        'is_admin' => false,
    ]);
}

}
