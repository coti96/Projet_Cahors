<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskType extends AbstractType

{
    private $entityManager;

     public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        $users = $this->entityManager->getRepository(User::class)->createQueryBuilder('u')
        ->getQuery()
        ->getResult();
    
    $userChoices = [];
    foreach ($users as $user) {
        $userChoices[$user->getIdentite()] = $user->getIdentite();
    }

   
       
        $builder
            ->add('title')
            ->add('description')
            ->add('assignedTo', ChoiceType::class, [
                'choices' => $userChoices,
                'required' => false,
                'label' => 'Assigné à : '
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
            ]);
        
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
