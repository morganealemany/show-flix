<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ]);

            // On va se brancher à l'événement PRE_SET_DATA pour afficher le champ password en fonction du contexte :
            // - à la création : on affiche le champ password
            // - à l'édition : on n'affiche pas le champ password


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
        {
            // On récupère les données de l'utilisateur que l'on s'apprête à créer ou à éditer
            $user = $event->getData();
            $form = $event->getForm();
            // dd($event, $user, $form);

            // Si on est dans le cas d'une création de compte utilisateur alors on ajoute le champ password
            if ($user->getId() === null) {
                $form->add('plainPassword', PasswordType::class, [

                    // On indique à Symfony que la propriété 'plainPassword'
                    // n'est pas liée (mapped) à l'entité User
                    'mapped' => false
                ]);
            }
            // dd($user, $form);
        });
    }
            
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

