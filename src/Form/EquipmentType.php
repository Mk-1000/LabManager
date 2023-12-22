<?php

namespace App\Form;
use Doctrine\ORM\EntityRepository;

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
                'label'=>"allez-vous l'utiliser maintenant ?",'attr'=>[
                    'class'=>'box'
                ]
            ])
            ->add('projectDeRecherche', EntityType::class, [
                'class' => ProjectDeRecherche::class,
                'label'=>"l'équipement sera utilisé dans quel projet",
                'choice_label' => 'titre', // Assuming 'titre' is the field in ProjectDeRecherche to be shown as the label
                'placeholder' => 'Select Project', // Set a placeholder for the dropdown
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.chercheur', 'c')
                        ->where('c.id = :chercheurId')
                        ->setParameter('chercheurId', 72);
                },])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }
}
