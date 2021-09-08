<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('episodeNumber', null, [
                'label' => 'Numéro de l\'épisode'
            ])
            ->add('title', null, [
                'label' => 'Titre'
            ])
            // ->add('publishedAt')
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('season', null, [
                'label' => 'Saison'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
