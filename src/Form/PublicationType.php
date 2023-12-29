<?php

namespace App\Form;

use App\Entity\Publication;
use App\Entity\ProjectDeRecherche;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUserId = $options['current_user_id'];

        $builder
            ->add('titre',TextType::class,[
                'attr'=>[
                'class' =>'form-control inp',
                'id'=>'searchPublication',
                ]
            ])

            // ->add('auteurs')

            ->add('motsCles',TextType::class,[
                'attr'=>[
                'class' =>'form-control inp',
                'id'=>'searchPublication',
                ]
            ])
            ->add('projectDeRecherche', EntityType::class, [
                'class' => ProjectDeRecherche::class,
                'choice_label' => 'titre',
                'placeholder' => 'Select Project', // Set a placeholder for the dropdown
                'query_builder' => function (EntityRepository $er) use ($currentUserId) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.chercheur', 'c')
                        ->where('c.id = :chercheurId')
                        ->setParameter('chercheurId', $currentUserId);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
            'current_user_id' => null, // Add a default value for current_user_id option

        ]);
    }
}
