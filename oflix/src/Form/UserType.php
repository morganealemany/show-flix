<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'USER' => 'ROLE_USER',
                    'ADMIN' => 'ROLE_ADMIN',
                    'SUPER_ADMIN' => 'ROLE_SUPER_ADMIN'
                ],
                'expanded' => true, // checkbox
                'multiple' => true, // choix multiple
                // https://www.developpez.net/forums/d1860599/php/bibliotheques-frameworks/symfony/formulaire-ne-s-affiche-error-array-to-string-conversion-sf4/
            ])
            // ->add('Password')
            ->add('plainPassword', PasswordType::class, [
                // On indique à symfony que la propriété PlainPassword n'est pas lié (mapped) à l'entité User.
                'mapped' =>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
