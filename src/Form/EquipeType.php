<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomEquipe')
            ->add('jeuEquipe')
            ->add('logoEquipe',FileType::class, array('data_class' => null ,
                'label' => 'Selectionnez image  de equipe',
                'required'   => false,

            ))
            ->add('idJ1')
            ->add('idJ2')
            ->add('idJ3')
            ->add('idJ4')
            ->add('idJ5')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
