<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Documents;
use App\Entity\Routeur;
use App\Form\RouteurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;

class RouteurController extends AbstractController
{
    public function showmore(ManagerRegistry $doctrine,Routeur $routeur): Response
    {   $repository = $doctrine->getRepository (persistentObject:Routeur::class); 
        $routeur = $repository->find($routeur->getId());
        $repository = $doctrine->getRepository (persistentObject:Documents::class);
        $documents = $repository->findBy(['routeur'=> $routeur->getId()]);
        return $this->render('projet/showmorerouteur.html.twig', [
            'routeur' => $routeur,
            'documents' => $documents,
        ]);
    }

        public function createRouteur(Routeur $routeur = null,Request $request,ManagerRegistry $doctrine)
{   
        if (!$routeur)
        {
        $routeur = new Routeur();
        } 
        $formRouteur = $this->createForm(RouteurType::class,$routeur);
        
        $formRouteur->handleRequest($request);
        if($formRouteur->isSubmitted() && $formRouteur->isValid() )
        {
             //On recupere le champs document
             $documents = $formRouteur->get('documents')->getData();
    
             // On boucle sur les doc
              foreach($documents as $document){
             // On génère un nouveau nom de fichier
             $fichier = md5(uniqid()).'.'.$document->guessExtension();
             
             // On copie le fichier dans le dossier uploads
             $document->move(
                 $this->getParameter('documents_directory'),
                 $fichier
             );
             
             // On crée un nouvel objet de type Document
             $doc = new Documents();
             $doc->setName($fichier);
     
             //On rattache le document a l'objet routeur
             $routeur->addDocument($doc);
          
         }
             //Fin du foreach
            $entityManager = $doctrine->getManager();
            $entityManager->persist($routeur);
            $entityManager->flush();
            $this->addFlash(type:'success',message:"Le routeur a été enregistré avec succès");
            return $this->redirectToRoute('show');
        }
        else 
        {
        return $this->renderForm('projet/edit/createRouteur.html.twig',[
            'formRouteur'=> $formRouteur,
            'editMode' =>$routeur->getId() !==null
        ]);

        }
}
public function deleteRouteur(Routeur $routeur = null,ManagerRegistry $doctrine): RedirectResponse {
    if ($routeur)   
    {
    $entityManager=$doctrine->getManager();
    $entityManager->remove($routeur);
    $entityManager->flush();
    $this->addFlash(type:'success',message:"Le routeur a été supprimé avec succès");
    } 
    else
    {
        $this->addFlash(type:'error', message:"Routeur inexistant");
    }
    return $this->redirectToRoute(route:'show');
}

}


