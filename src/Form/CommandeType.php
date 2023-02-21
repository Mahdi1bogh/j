<?php

namespace App\Form;

use App\Entity\Commande;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('totalPoints')
            ->add('idUser')
            ->add('dateCommande', DateType::class, [

            ])
            ->add('adresse')
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'En cours' => 'En cours',
                    'En attente' => 'En attente',
                    'Livrée' => 'Livrée',
                ],
            ])
            ->add('captchaCode', \Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType::class, array(
            'captchaConfig' => 'ExampleCaptchaUserRegistration',
            'constraints' => [
                new ValidCaptcha([
                    'message' => 'Invalid captcha, please try again',
                ]),
            ],
        ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
