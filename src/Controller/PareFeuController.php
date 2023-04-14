<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Documents;
use App\Entity\PareFeu;
use App\Form\PareFeuType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;

class PareFeuController extends AbstractController
{
    public function showmore(ManagerRegistry $doctrine,PareFeu $parefeu): Response
    {   $repository = $doctrine->getRepository (persistentObject:Parefeu::class); 
        $parefeu = $repository->find($parefeu->getId());
        $repository = $doctrine->getRepository (persistentObject:Documents::class);
        $documents = $repository->findBy(['parefeu'=> $parefeu->getId()]);
        return $this->render('projet/showmoreparefeu.html.twig', [
            'parefeu' => $parefeu,
            'documents' => $documents,
        ]);
    }

        public function createparefeu(PareFeu $parefeu = null,Request $request,ManagerRegistry $doctrine)
{   
        if (!$parefeu)
        {
        $parefeu = new PareFeu();
        } 
        $formparefeu = $this->createForm(parefeuType::class,$parefeu);
        
        $formparefeu->handleRequest($request);
        if($formparefeu->isSubmitted() && $formparefeu->isValid() )
        {
             //On recupere le champs document
             $documents = $formparefeu->get('documents')->getData();
    
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
     
             //On rattache le document a l'objet parefeu
             $parefeu->addDocument($doc);
          
         }
             //Fin du foreach
            $entityManager = $doctrine->getManager();
            $entityManager->persist($parefeu);
            $entityManager->flush();
            $this->addFlash(type:'success',message:"Le parefeu a été enregistré avec succès");
            return $this->redirectToRoute('show');
        }
        else 
        {
        return $this->renderForm('parefeu/create.html.twig',[
            'formParefeu'=> $formparefeu,
            'editMode' =>$parefeu->getId() !==null
        ]);

        }
}
public function deleteparefeu(PareFeu $parefeu = null,ManagerRegistry $doctrine): RedirectResponse {
    if ($parefeu)   
    {
    $entityManager=$doctrine->getManager();
    $entityManager->remove($parefeu);
    $entityManager->flush();
    $this->addFlash(type:'success',message:"Le parefeu a été supprimé avec succès");
    } 
    else
    {
        $this->addFlash(type:'error', message:"parefeu inexistant");
    }
    return $this->redirectToRoute(route:'show');
}

}


