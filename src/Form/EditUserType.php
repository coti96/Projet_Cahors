<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir une adresse email'
                    ])
                    ],
                    'label' => 'E-mail',
                    'required' => true, 
                    'attr' => [
                    'class' => 'form-control'
                    ]
            ])
            ->add('identite', null, [
                'label' => 'Nom Prenom'])
            
            ->add('roles',ChoiceType::class,[
                'choices'=> [
                'Utilisateur' => 'ROLE_USER',
                'Administrateur'=>'ROLE_ADMIN',
                'Editeur' => 'ROLE_EDITOR'
                ],
                'expanded'=> true,
                'label' => 'Rôles',
                'multiple' => true
                ])

                ->add('fonction')
            ->add('Valider',SubmitType::class) 
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
