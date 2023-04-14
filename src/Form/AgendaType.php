<?php

namespace App\Form;

use App\Entity\Agenda;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AgendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('start', DateTimeType::class, [
                'date_widget' => 'single_text',
                'label' => 'Début'
            ])
            ->add('end', DateTimeType::class, [
                'date_widget' => 'single_text',
                'label'=> 'Fin'
            ])
            ->add('description')
            ->add('text_color', ColorType::class,[
                'label' => 'Couleur du texte'
            ])
            ->add('background_color', ColorType::class,[
                'label' => 'Couleur du fond'
            ])
            ->add('Enregistrer', type:SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
        ]);
    }
}