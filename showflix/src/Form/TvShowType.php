<?php

namespace App\Form;

use App\Entity\TvShow;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('synopsis')
            ->add('imageTvShow', FileType::class, [
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
            ->add('nbLikes')
            ->add('publishedAt', null, [
                'label' => 'Date de publication'
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('characters', null, [
                'label' => 'Personnages'
            ])
            ->add('categories', null, [
                'label' => 'Catégories'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TvShow::class,
        ]);
    }
}
