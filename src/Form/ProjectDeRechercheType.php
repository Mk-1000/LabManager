<?php

namespace App\Form;

use App\Entity\Chercheur;
use App\Entity\ProjectDeRecherche;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectDeRechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('etatAvancement')
            ->add('chercheur', EntityType::class, [
                'class' => Chercheur::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectDeRecherche::class,
        ]);
    }
}
