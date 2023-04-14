<?php

namespace App\Form;

use App\Entity\PareFeu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Serveur;
use App\Entity\Commutateur;
use App\Entity\Routeur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class PareFeuType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $serveurs = $this->entityManager->getRepository(Serveur::class)->findAll();
        $serveurChoices = [];
        foreach ($serveurs as $serveur) {
            $serveurChoices[$serveur->getNom()] = $serveur->getNom();
        }

        $routeurs = $this->entityManager->getRepository(Routeur::class)->findAll();
        $routeurChoices = [];
        foreach ($routeurs as $routeur) {
            $routeurChoices[$routeur->getNom()] = $routeur->getNom();
        }

        $commutateurs = $this->entityManager->getRepository(Commutateur::class)->findAll();
        $commutateurChoices = [];
        foreach ($commutateurs as $commutateur) {
            $commutateurChoices[$commutateur->getNom()] = $commutateur->getNom();
        }
        foreach ($commutateurs as $commutateur) {
            $choices['Commutateur - ' . $commutateur->getNom()] = $commutateur->getNom();
        }
        $parefeux = $this->entityManager->getRepository(Parefeu::class)->findAll();
        $parefeuChoices = [];
        foreach ($parefeux as $parefeu) {
            $parefeuChoices[$parefeu->getNom()] = $parefeu->getNom();
        }
        $builder
            ->add('Nom', TextType::class, [
                'attr' => [
                    'placeholder' => "Nom"
                ]
            ])

            ->add('Editeur', TextType::class, [
                'attr' => [
                    'placeholder' => "Editeur"
                ]
            ])
            ->add('IP', TextType::class, [
                'label' => 'IP',
                'attr' => [
                    'placeholder' => "IP"
                ]])
            ->add('Emplacement', TextType::class, [
                'attr' => [
                    'placeholder' => "Emplacement"
                ]
            ])
            ->add('Rack', TextType::class, [
                'attr' => [
                    'placeholder' => "Rack du routeur"
                ]
            ])
            ->add('date', DateTimeType::class, [
                'date_widget' => 'single_text',
                'label' => 'Date de mise en service'
            ])
            ->add('Parent', ChoiceType::class, [
                'required' => false,
                'label' => 'Ajouter le matériel dont ce routeur dépend',
                'choices' => [
                    '' => '',
                    'Serveurs' => $serveurChoices,
                    'Routeurs' => $routeurChoices,
                    'Commutateurs' => $commutateurChoices,
                    'PareFeux' => $parefeuChoices,
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('documents', FileType::class, [
                'label' => 'Ajouter un ou plusieurs fichiers',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id'=> 'formFileMultiple'
                ]
            ])
            ->add('Enregistrer', type: SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PareFeu::class,
        ]);
    }
}


