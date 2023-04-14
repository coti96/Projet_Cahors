<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\AbstractManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminController extends AbstractController
{  
   






    /*Liste les utilisateurs du site
    *
   */


    public function usersList(UserRepository $users)
    {
        return $this->render("admin/users.html.twig",[
            'users' => $users->findAll()
          
        ]);
    }

    public function editUser(User $user,Request $request, ManagerRegistry $doctrine)
    {
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
    
        {   
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        
            $this->addFlash('message','Utilisateur modifié avec succes');
            return $this->redirectToRoute('users');

        }
        return $this->render('admin/edituser.html.twig',[
            'userForm'=>$form->createView()
        ]);
    }

    public function deleteUser(User $user,ManagerRegistry $doctrine): RedirectResponse 
    {
    if ($user)   
    {
    $entityManager=$doctrine->getManager();
    $entityManager->remove($user);
    $entityManager->flush();
    $this->addFlash(type:'success',message:"L'utilisateur a été supprimé avec succès");
    } 
    else
    {
        $this->addFlash(type:'error', message:"Erreur");
    }
    return $this->redirectToRoute(route:'users');
}
}

    
