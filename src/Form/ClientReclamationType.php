<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeReclam',ChoiceType::class, [
                'choices'  => [
                    'technique' => 'technique',
                    'commerciale' => 'commerciale',
                ],
            ])
            ->add('motifReclam')
//            ->add('etatReclam',ChoiceType::class, [
//        'choices'  => [
//            'En cours' => 'En cours',
//            'En attente' => 'En attente',
//            'cloture' => 'cloture',
//        ],
//    ])
            ->add('messageReclam')
//            ->add('idUser')
//            ->add('dateReclam',DateTimeType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
