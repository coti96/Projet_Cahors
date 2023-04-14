<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Documents;
use App\Entity\Routeur;
use App\Entity\Serveur;
use App\Entity\PareFeu;
use App\Entity\Commutateur;
use App\Form\SearchType;
use App\Form\ServeurType;
use App\Repository\CommutateurRepository;
use App\Repository\ContactsRepository;
use App\Repository\DocumentsRepository;
use App\Repository\PareFeuRepository;
use App\Repository\RouteurRepository;
use App\Repository\ServeurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request; 


class ProjetController extends AbstractController

{   
    public function index (
        Request $request,
        ServeurRepository $serveurRepository,
        CommutateurRepository $commutateurRepository,
        DocumentsRepository $documentsRepository,
        ContactsRepository $contactsRepository, 
        RouteurRepository $routeurRepository,
        PareFeuRepository $parefeuRepository,
        $resServeur = null,
        $resRouteur = null,
        $resCommutateur =null,
        $resContact=null,
        $resDoc=null,
        $resParefeu=null,
        ):Response {

       
        $form = $this->createForm(SearchType::class);
        $search=$form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid())
        { //Recherche des annonces correspondantes aux mots clés

            $resServeur = $serveurRepository->search($search->get('mots')->getData());
            $resCommutateur= $commutateurRepository->search($search->get('mots')->getData());
            $resDoc= $documentsRepository->search($search->get('mots')->getData());
            $resContact = $contactsRepository->search($search->get('mots')->getData());
            $resRouteur = $routeurRepository->search($search->get('mots')->getData());
            $resParefeu = $parefeuRepository->search($search->get('mots')->getData());
        }
        return $this->render('projet/home.html.twig', [
        'serveurs'=> $resServeur,
        'routeurs'=> $resRouteur,
        'commutateurs' => $resCommutateur,
        'documents' => $resDoc,
        'contacts' => $resContact,
        'parefeux' => $resParefeu,
         'form' => $form->createView() 
        ]);
    }
   
    public function showmore(ManagerRegistry $doctrine,Serveur $serveur): Response
    {   $repository = $doctrine->getRepository (persistentObject:Serveur::class); 
        $serveur = $repository->find($serveur->getId());
        $repository = $doctrine->getRepository (persistentObject:Documents::class);
        $documents = $repository->findBy(['serveur'=> $serveur->getId()]);
        return $this->render('projet/showmore.html.twig', [
            'serveur' => $serveur,
            'documents' => $documents,
        ]);
    }

   

    public function show(ManagerRegistry $doctrine, Request $request): Response
    {   $repository = $doctrine->getRepository (persistentObject:Serveur::class);
        $serveurs = $repository->findAll();
        $nbServeurs=count($serveurs);
        $repository = $doctrine->getRepository (persistentObject:Routeur::class);
        $routeurs = $repository->findAll(); 
        $nbRouteurs =count($routeurs);
        $repository = $doctrine->getRepository (persistentObject:Commutateur::class);
        $commutateurs = $repository->findAll();
        $nbCommutateurs =count($commutateurs);
        $repository = $doctrine->getRepository (persistentObject:PareFeu::class);
        $parefeux = $repository->findAll();
        $nbparefeu =count($parefeux);

        $request->query->get('page');
        
        return $this->render('projet/show.html.twig',[
            'nbcommutateur'=> $nbCommutateurs,
            'nbserveur' => $nbServeurs,
            'nbrouteur' => $nbRouteurs,
            'serveurs'=> $serveurs,
            'routeurs'=>$routeurs,
            'commutateurs'=>$commutateurs,
            'parefeux' => $parefeux,
            'nbparefeu' => $nbparefeu,
          
        ]);
    }
  
  
    public function create(Serveur $serveur=null,Request $request,ManagerRegistry $doctrine)
    {   
        
        //Si la variable serveur est null on crée un nouveau objet serveur
        if (!$serveur) 
        {
            $serveur = new Serveur();
        } 

        //Creation du formulaire de type Serveur

        $formServeur = $this->createForm(ServeurType::class,$serveur);
        $formServeur->handleRequest($request);

        //Si le formulaire est valide et bien remplie alors
        if($formServeur->isSubmitted() && $formServeur->isValid()) {
        //On recupere le champs document
            $documents = $formServeur->get('documents')->getData();
            

    
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

        //On rattache le document a l'objet serveur
        $serveur->addDocument($doc);
     
    }
        //Fin du if 
       
        //On enregistre le tout dans la base de donnée
        $entityManager = $doctrine->getManager();
        $entityManager->persist($serveur);
        $entityManager->flush();
       
     
        $this->addFlash(type:'success',message:"Le serveur a été enregistré avec succès");
            return $this->redirectToRoute('show');

        //Sinon on retourne le formulaire 
        //Si la requete contient le mode "edit" on préremplie le formulaire avec les infos du serveur en question
        } else {
            $repository = $doctrine->getRepository (persistentObject:Documents::class);
            $documents = $repository->findBy(['serveur'=> $serveur->getId()]);
            $request->query->get('page');
            return $this->renderForm('projet/edit/create.html.twig',[
                'formServeur'=> $formServeur,
                'documents' => $documents,
                'editMode' =>$serveur->getId() !==null
            ]);
        }
    }
    


    public function delete(Serveur $serveur = null,ManagerRegistry $doctrine): RedirectResponse 
    {
    if ($serveur)   
    {
    $entityManager=$doctrine->getManager();
    $entityManager->remove($serveur);
    $entityManager->flush();
    $this->addFlash(type:'success',message:"Le serveur a été supprimé avec succès");
    } 
    else
    {
        $this->addFlash(type:'error', message:"Serveur");
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
