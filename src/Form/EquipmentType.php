<?php

namespace App\Form;

use App\Entity\Equipment;
use App\Entity\ProjectDeRecherche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=>[
                'class' =>'form-control inp',
                'id'=>'searchPublication',
                ]
            ])
            ->add('etat',null,[
                'label'=>'disponible?','attr'=>[
                    'class'=>'box'
                ]
            ])
            ->add('projectDeRecherche', EntityType::class, [
                'class' => ProjectDeRecherche::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }
}
