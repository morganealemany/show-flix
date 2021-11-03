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
            ->add('imgUpload', FileType::class, [
                'label' => 'Choisir une image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Merci de ne choisir que des fichiers .png et .jpeg',
                    ])
                ],
            ])
            ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}
