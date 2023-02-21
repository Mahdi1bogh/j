<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produits;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;



class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_produit',TextType::class,[
                'required'=>false,
                'empty_data' => ''
            ])
            ->add('nb_pts')
            
            ->add('imageFile',VichImageType::class,[
                'required'=>false,
                'empty_data' => ''
            ])
            ->add('id_categorie', EntityType::class,[
                'class'=>Categorie::class,
                'property_path'=>'id_categorie',
                'empty_data' => ''
            ])
            ->add('quantite')
            ->add('ajouter',SubmitType::class,)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
