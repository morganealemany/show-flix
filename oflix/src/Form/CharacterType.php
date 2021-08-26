<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Renseigner le prénom',
                ]
            ])
            ->add('lastname', null, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Renseigner le nom',
                ]
            ])
            ->add('gender', null, [
                'label' => 'Genre',
                'attr' => [
                    'placeholder' => 'Renseigner le genre',
                ]
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Biographie',
                'attr' => [
                    'placeholder' => 'Renseigner la biographie',
                ]
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => [
                    'placeholder' => 'Renseigner l\'âge',
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('tvShows')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}
