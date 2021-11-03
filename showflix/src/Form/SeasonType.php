<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seasonNumber', null, [
                'label' => 'Numéro de saison'
            ])
            ->add('publishedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('tvShow', null, [
            //     'label' => 'Séries'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
