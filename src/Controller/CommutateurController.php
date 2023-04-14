<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Commutateur;
use App\Form\CommutateurType;
use App\Entity\Documents;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;



class CommutateurController extends AbstractController
{
    public function showmore(ManagerRegistry $doctrine,Commutateur $commutateur): Response
    {   $repository = $doctrine->getRepository (persistentObject:Commutateur::class); 
        $commutateur = $repository->find($commutateur->getId());
        $repository = $doctrine->getRepository (persistentObject:Documents::class);
        $documents = $repository->findBy(['commutateur'=> $commutateur->getId()]);
        return $this->render('projet/showmorecommutateur.html.twig', [
            'commutateur' => $commutateur,
            'documents' => $documents,
        ]);
    }
    public function create(Commutateur $commutateur=null ,Request $request,ManagerRegistry $doctrine)
    {   
        
        //Si la variable commutateur est null on crée un nouveau objet commutateur
        if (!$commutateur) 
        {
            $commutateur= new Commutateur();
        } 

        //Creation du formulaire de type Commutateur

        $form = $this->createForm(CommutateurType::class,$commutateur);
        $form->handleRequest($request);

        //Si le formulaire est valide et bien remplie alors
        if($form->isSubmitted() && $form->isValid()) {
        //On recupere le champs document
            $documents = $form->get('documents')->getData();
    
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

        //On rattache le document a l'objet appli
        $commutateur->addDocument($doc);
     
    }
        //Fin du if 
         
        //On enregistre le tout dans la base de donnée
        $entityManager = $doctrine->getManager();
        $entityManager->persist($commutateur);
        $entityManager->flush();
       
     
        $this->addFlash(type:'success',message:"Le commutateur a été enregistré avec succès");
            return $this->redirectToRoute('show');

        //Sinon on retourne le formulaire 
        //Si la requete contient le mode "edit" on préremplie le formulaire avec les infos du serveur en question
        } else {
            $repository = $doctrine->getRepository (persistentObject:Documents::class);
            //On cherche les documents correspondants par 
            $documents = $repository->findBy([
                'id'=> $commutateur->getId()]);
            $request->query->get('page');
            return $this->renderForm('commutateur/new.html.twig',[
                'form'=> $form,
                'documents' => $documents,
                'editMode' =>$commutateur->getId() !==null
            ]);
        }
    }

    public function delete(Commutateur $commutateur = null,ManagerRegistry $doctrine): RedirectResponse 
    {
    if ($commutateur)   
    {
    $entityManager=$doctrine->getManager();
    $entityManager->remove($commutateur);
    $entityManager->flush();
    $this->addFlash(type:'success',message:"Le commutateur a été supprimée avec succès de la base de données");
    } 
    else
    {
        $this->addFlash(type:'error', message:"Erreur: Commutateur non supprimée");
    }
    return $this->redirectToRoute(route:'show');
}

public function deletedoc(Documents $document, Request $request, ManagerRegistry $doctrine){
        
   
        // On récupère le nom de l'image
        $nom = $document->getName();
        // On supprime le fichier
        unlink($this->getParameter('documents_directory').'/'.$nom);

        // On supprime l'entrée de la base
        $entityManager=$doctrine->getManager();
        $entityManager->remove($document);
        $entityManager->flush();

        // On répond en json
        return $this->redirectToRoute(route:'show');
    
}
}
