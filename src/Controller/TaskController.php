<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DocumentsRepository;
use App\Entity\Documents;
Use Doctrine\ORM\EntityManager;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }
   
  
    #[Route('/new', name: 'app_task_new', methods: ['GET','POST'])]
    public function new(Request $request,Task $task=null, ManagerRegistry $doctrine): Response
    {
        //Si la variable  est null on crée un nouveau objet 
        if (!$task) 
        {
            $task = new Task();
        } 
        
        $form = $this->createForm(TaskType::class, $task);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
               $entityManager = $doctrine->getManager();
      
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
         
            //On rattache le document a l'objet 
            $task->addDocument($doc);
        }
            
            // Enregistrer la tâche
            
            $entityManager->persist($task);
            $entityManager->flush();

            
            $this->addFlash(type:'success',message:"La tache a été enregistrée avec succès");
          
    
            return $this->redirectToRoute('app_task_index');
        }
    
        return $this->renderForm('task/new.html.twig', [
                'form' => $form]);
        
       
    
}
  
    

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskRepository->save($task, true);

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $taskRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

    
#[Route('/{id}/complete', name: 'app_task_complete', methods: ['POST'])]
public function complete(Task $task, Request $request, EntityManagerInterface $entityManager): JsonResponse
{ //Prend en parametre l'id de la tache, et l'entityManager

    //récupère le contenu de la demande POST en format JSON.
    $data = json_decode($request->getContent(), true);

    //récupère la valeur de la clé 'completed' dans le tableau
    $completed = $data['completed'] ?? false;
    //Si cette clé n'existe pas, elle renvoie false par défaut grâce à l'opérateur de coalescence null (??).

    //Met a jour dans la base de donnée le completeté
    $task->setCompleted($completed);
    //Enregistre
    $entityManager->flush();

    return new JsonResponse(['success' => true]);
}

}
