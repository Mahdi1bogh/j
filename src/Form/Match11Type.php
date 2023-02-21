<?php

namespace App\Form;

use App\Entity\Match1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Match11Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_equipe1')
            ->add('nom_equipe2')
            ->add('date_match1')
            ->add('resultat_match1')
            ->add('lieu_match1')
            ->add('Email_lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Match1::class,
        ]);
    }
}
