<?php

namespace App\Form;

use App\Entity\TvShow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('synopsis')
            ->add('image')
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
