<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Repository\ContactsRepository;
use App\Form\ContactType;
use App\FOrm\MailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mime\Email;



class ContactController extends AbstractController
{  
    /*Liste les contacts de l'application
    *
   */


    public function contactsList(ContactsRepository $contacts)
    {
        return $this->render("admin/contact/contacts.html.twig",[
            'contacts' => $contacts->findAll()
          
        ]);
    }


    public function deleteContact (Contacts $contact,ManagerRegistry $doctrine): RedirectResponse 
    {
    if ($contact)   
    {
    $entityManager=$doctrine->getManager();
    $entityManager->remove($contact);
    $entityManager->flush();
    $this->addFlash(type:'success',message:"Le contact a été supprimé avec succès");
    } 
    else
    {
        $this->addFlash(type:'error', message:"Erreur");
    }
    return $this->redirectToRoute(route:'contacts');
}


/*Fonction register : fonction qui permet d'enregistrer un nouveau contact*/

public function createcontact(Contacts $contact=null,Request $request,ManagerRegistry $doctrine)
    {   
        if (!$contact) {
            $contact = new Contacts();
        } 
        $formcontact = $this->createForm(ContactType::class,$contact);
        $formcontact->handleRequest($request);
        if($formcontact->isSubmitted() && $formcontact->isValid()) {
            $entityManager = $doctrine->getManager();
            
            
            
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash(type:'success',message:"Le contact a été enregistré avec succès");
            return $this->redirectToRoute('contacts');
        } else {
            return $this->renderForm('admin/contact/register.html.twig',[
                'contactForm'=> $formcontact,
                'editMode' =>$contact->getId() !==null
            ]);
        }
}

public function mailtocontact(Request $request, MailerInterface $mailer, Contacts $contact): Response
    {
        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // Envoie un email vers le contact
         // Initiation de l'objet email
         $mail = $this->getParameter('MAIL');
         $mailpsw = $this->getParameter('MAILPSW');
             
         $transport = new GmailSmtpTransport($mail, $mailpsw);
         $mailer = new Mailer($transport);
         $email = (new Email())
         ->from('projetsymfony@gmail.com')
         ->to($contact->getMail())
         ->subject($form->get('sujet')->getData())
         ->text($form->get('body')->getData())
     ;

    
    $mailer->send($email); 
    $this->addFlash(type:'success',message:"Le message a été envoyé avec succès");
    return $this->redirectToRoute(route:'contacts');


}
//Fin du if

// On retourne sur la page 'contact'
return $this->renderForm('admin/contact/mail.html.twig',[
    'form'=> $form,
    'destinataire' =>$contact->getIdentite() 
]);

    
}
}