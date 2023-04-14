<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Form\DocumentsType;
use App\Repository\DocumentsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/documents')]
class DocumentsController extends AbstractController
{
    #[Route('/', name: 'app_documents_index', methods: ['GET'])]
    public function index(DocumentsRepository $documentsRepository): Response
    {
        return $this->render('documents/show.html.twig', [
            'documents' => $documentsRepository->findAll(),
        ]);
    }

  
       
  
    public function new(Documents $document=null,Request $request, ManagerRegistry $doctrine): Response
    {  $document = new Documents();
         //Creation du formulaire 
        $form = $this->createForm(DocumentsType::class);
        $form->handleRequest($request);
        //Si le formulaire est valide et bien remplie alors
        if ($form->isSubmitted() && $form->isValid()) {

             //On recupere le champs document
             $docs = $form->get('documents')->getData();
           // On boucle sur les doc
             foreach($docs as $doc){
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()).'.'.$doc->guessExtension();
            
            // On copie le fichier dans le dossier uploads
            $doc->move(
                $this->getParameter('documents_directory'),
                $fichier
            );
            
            // On crée un nouvel objet de type Document
           
            $document->setName($fichier);
            $document->setDescription($form->get('description')->getData());
            $document->setServeur($form->get('serveur')->getData());
 
    
             }
    
             
            //On enregistre le tout dans la base de donnée
            $entityManager = $doctrine->getManager();
            $entityManager->persist($document);
            $entityManager->flush();
           
           

            return $this->redirectToRoute('home');
        }else {

        return $this->renderForm('documents/new.html.twig', [
           
            'form' => $form,
        ]);
    }
}

    public function show(ManagerRegistry $doctrine, Request $request): Response
    {   $repository = $doctrine->getRepository (persistentObject:Documents::class);
        $documents = $repository->findAll();
        $request->query->get('page');
        return $this->render('documents/show.html.twig', $parameters =[
            'documents' => $documents,
        ]);
    }

    #[Route('/documents_edit', name: 'app_documents_edit', methods: ['POST'])]
    public function edit(Request $request, Documents $document, DocumentsRepository $documentsRepository): Response
    {
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentsRepository->save($document, true);

            return $this->redirectToRoute('app_documents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('documents/edit.html.twig', [
            'documents' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/documents_delete', name: 'app_documents_delete', methods: ['POST'])]
    public function delete(Request $request, Documents $document, DocumentsRepository $documentsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $documentsRepository->remove($document, true);
        }

        return $this->redirectToRoute('app_documents_index', [], Response::HTTP_SEE_OTHER);
    }

    

}
