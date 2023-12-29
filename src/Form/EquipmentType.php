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
        $currentUserId = $options['current_user_id'];

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
                'label' => "l'équipement sera utilisé dans quel projet",
                'choice_label' => 'titre',
                'placeholder' => 'Select Project',
                'query_builder' => function (EntityRepository $er) use ($currentUserId) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.chercheur', 'c')
                        ->where('c.id = :chercheurId')
                        ->setParameter('chercheurId', $currentUserId);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
            'current_user_id' => null, // Add a default value for current_user_id option
        ]);
    }
}
