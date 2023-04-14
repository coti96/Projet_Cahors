<?php

namespace App\Form;

use App\Entity\Documents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Serveur;
use App\Entity\Routeur;
use App\Entity\Commutateur;
use App\Entity\PareFeu;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;




class DocumentsType extends AbstractType
{  private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { $serveurs = $this->entityManager->getRepository(Serveur::class)->findAll();
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
        ->add('documents', FileType::class, [
            'label' => 'Ajouter un fichier',
            'multiple' => true,
            'mapped' => false,
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'id'=> 'formFileMultiple'
            ]
        ])
        ->add('name',TextType::class, [
            'label' => 'Titre',
            'required' => true,
           
        ])
        ->add('description',TextType::class, [
            'label' => 'Ajouter une description',
            'required' => true,
           
        ])
        ->add('serveur', ChoiceType::class, [
            'label' => 'Ce document est en rapport avec le matériel suivant ',
            'choices' => [
                'Serveurs' => $serveurChoices,
                'Routeurs' => $routeurChoices,
                'Commutateurs' => $commutateurChoices,
                'Parefeux' => $parefeuChoices,
            ],
            'attr' => ['class' => 'form-control'],
        ])
        
        ->add('Enregistrer', SubmitType::class)
        ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
