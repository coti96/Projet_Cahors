<?php

namespace App\Form;

use App\Entity\Serveur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\PareFeu;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commutateur;
use App\Entity\Routeur;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;





class ServeurType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   $serveurs = $this->entityManager->getRepository(Serveur::class)->findAll();
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
        $parefeux = $this->entityManager->getRepository(PareFeu::class)->findAll();
        $parefeuChoices = [];
        foreach ($parefeux as $parefeu) {
            $parefeuChoices[$parefeu->getNom()] = $parefeu->getNom();
        }
        $builder
            ->add('Nom',TextType::class,
            ['attr' =>
            ['placeholder'=>"Nom du Serveur"
            ]
            ] )
            ->add('IP', TextType::class, [
                'label' => 'IP',
              ])
           
            ->add('Marque', TextType::class,
            ['attr' =>
            ['placeholder'=>"Marque du Serveur"
            ]
            ])
            ->add('Emplacement', TextType::class,
            ['attr' =>
            ['placeholder'=>"Emplacement"
            ]
            ])
            ->add('Rack', TextType::class,
            ['attr' =>
            ['placeholder'=>"Rack du Serveur"
            ]
            ])
            ->add('date', DateTimeType::class, [
                'date_widget' => 'single_text',
                'label' => 'Date de mise en service'
            ])  
            ->add('Parent', ChoiceType::class, [
                'label' => 'Ajouter le matériel dont ce serveur dépend',
                'required' => false,
                'choices' => [
                    '' => '',
                    'Serveurs' => $serveurChoices,
                    'Routeurs' => $routeurChoices,
                    'Commutateurs' => $commutateurChoices,
                    'Parefeux' => $parefeuChoices
                    
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

            ->add('Enregistrer', type:SubmitType::class)
            ->getForm();
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serveur::class,
        ]);
    }
}
