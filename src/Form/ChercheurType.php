<?php

namespace App\Form;

use App\Entity\Chercheur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChercheurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=>[
                'class' =>'form-control',
                'id'=>'searchPublication',
                ]
            ])
            ->add('prenom',TextType::class,[
                'attr'=>[
                'class' =>'form-control',
                'id'=>'searchPublication',
                ]
            ])
            ->add('email',TextType::class,[
                'attr'=>[
                'class' =>'form-control',
                'id'=>'searchPublication',
                ]
            ])
            ->add('motDePasse',TextType::class,[
                'attr'=>[
                'class' =>'form-control',
                'id'=>'searchPublication',
                ]
            ])
            ->add('role',TextType::class,[
                'attr'=>[
                'class' =>'form-control',
                'id'=>'searchPublication',
                ]
            ])
            ->setAttributes([
                'class' => 'signup-form',
                 // Add your desired form class here
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chercheur::class,
        ]);
    }
}
