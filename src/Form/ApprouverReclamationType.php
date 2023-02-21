<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApprouverReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('typeReclam',ChoiceType::class, [
//                'choices'  => [
//                    'technique' => 'technique',
//                    'commerciale' => 'commerciale',
//                ],
//            ])
//            ->add('motifReclam')
            ->add('etatReclam',ChoiceType::class, [
        'choices'  => [
            'En attente' => 'En attente',
            'En cours' => 'En cours',
            'cloture' => 'cloture',
            'refuser' => 'refuser',
        ],
    ])
//            ->add('messageReclam')
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
